<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Exception;
use Modules\Mercado\Application\CaixaApplication;
use Modules\Mercado\Application\MovimentacaoEstoqueApplication;
use Modules\Mercado\Application\VendaApplication;
use Modules\Mercado\Entities\Devolucao;
use Modules\Mercado\Entities\DevolucaoItem;
use Modules\Mercado\Repository\Caixa\CaixaRepository;
use Modules\Mercado\Repository\Cliente\ClienteRepository;
use Modules\Mercado\Repository\Devolucao\DevolucaoRepository;
use Modules\Mercado\Repository\Produto\ProdutoRepository;
use Modules\Mercado\Repository\Venda\VendaRepository;
use Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque\Requests\MovimentacaoEstoqueItemRequest;
use Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque\Requests\MovimentacaoEstoqueRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\DevolucaoVendaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\EditarStatusCaixaRequest;

class DevolucaoVenda
{
    private DevolucaoVendaRequest $request;
    public function __construct(DevolucaoVendaRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $this->validate();
        $devolucao = $this->criaOrAtualizaDevolucao();
        $itensDevolvidos = $this->criaDevolucaoItens($devolucao);
        $vendaPagamentoDevolucao = $this->criaVendaPagamentoDevolucao($devolucao);
        $venda = $this->atualizaVendaParaDevolucao($devolucao);

        $caixa = $this->atualizaStatusCaixa();

        return $venda;
    }

    private function validate()
    {
        $itens = [];

        foreach ($this->request->getItens() as $key => $item) {
            if (array_key_exists('devolucao', $item) && isset($item['quantidade_devolucao'])) {

                $quantidadeDevolucao = converteDinheiroParaFloat($item['quantidade_devolucao']);
                if ($quantidadeDevolucao == 0) {
                    throw new Exception("A quantidade de devolução deve ser maior que 0", 1);
                }

                $item['quantidade_devolucao'] =  $quantidadeDevolucao;
                $itens[] = $item;
            }
        }
        $this->request->setItens($itens); //atualiza os itens request com a quantidade convertida

        if (count($itens) == 0) {
            throw new Exception("Nenhum item foi selecionado para devolução", 1);
        }

        if (!$this->request->getCriarHistoricoRequest()->getComentario()) {
            throw new Exception("Informe o motivo", 1);
        }
        $produtosIds = array_column($this->request->getItens(), 'produtoId');
        $produtos = ProdutoRepository::getProdutoByIds($produtosIds);

        foreach ($this->request->getItens() as $key => $itemDevolucao) {

            $produtoDevolucao = $produtos->first(function ($produto) use ($itemDevolucao) {
                return $produto->id == $itemDevolucao['produtoId'];
            });
            $valor = $itemDevolucao['quantidade_devolucao'];
            $isFloat = intval($valor) != $valor;

            if (!$produtoDevolucao->pode_ser_float && $isFloat) {
                throw new Exception("O valor do material " . $produtoDevolucao->nome . 'não pode ser um valor fracionado. Unidade de medida: ' . $produtoDevolucao->sigla, 1);
            }
        }
    }

    private function atualizaVendaParaDevolucao(Devolucao $devolucao)
    {
        $status_id = config('config.status.devolucao');
        $venda = $devolucao->venda;

        $quantidadeTotaDevolvida = $venda->devolucao_itens->sum('quantidade');
        $quantidadeTotaItens = $venda->venda_itens->sum('quantidade');

        if ($quantidadeTotaItens != $quantidadeTotaDevolvida) {
            $status_id = config('config.status.devolucao_parcial');
        }

        // $this->atualizaValorToralDevolvido($devolucao);

        return VendaApplication::atualiza_status_venda($this->request->getVendaId(), $status_id, $this->request->getCriarHistoricoRequest());
    }

    private function criaOrAtualizaDevolucao()
    {
        $devolucao = DevolucaoRepository::getDevolucaoVendaByCaixa($this->request->getVendaId(), $this->request->getCaixaId(), $this->request->getCaixaEvidenciaId());

        $data = now();
        $totalDevolvido = $this->calculaVendaPagamentosDevolucao();

        if (!$devolucao) {
            return DevolucaoRepository::criar_devolucao(
                $this->request->getVendaId(),
                $this->request->getCaixaId(),
                $this->request->getCaixaEvidenciaId(),
                $this->request->getLojaId(),
                $this->request->getUsuarioId(),
                $data,
                $totalDevolvido,
                $this->request->getCriarHistoricoRequest()->getComentario(),
                $this->request->getCriarHistoricoRequest()
            );
        } else {

            return DevolucaoRepository::atualiza_devolucao(
                $devolucao->id,
                $devolucao->venda_id,
                $this->request->getCaixaId(),
                $this->request->getCaixaEvidenciaId(),
                $this->request->getLojaId(),
                $this->request->getUsuarioId(),
                $data,
                ($devolucao->total_devolvido + $totalDevolvido),
                $this->request->getCriarHistoricoRequest()->getComentario(),
                $this->request->getCriarHistoricoRequest()
            );
        }
    }

    private function criaVendaPagamentoDevolucao(Devolucao $devolucao)
    {
        $vendaPagamentos = $devolucao->venda->venda_pagamentos;
        // $totalDevolvido = $devolucao->venda->devolucoes->sum('total_devolvido'); // Total já devolvido
        // $valorTotalDevolucao = intval($totalDevolvido); // Total permitido (em centavos)
        // $sobra = $valorTotalDevolucao;
        $sobra = $devolucao->total_devolvido;

        $vendaPagamentosDevolucao = [];

        foreach ($vendaPagamentos as $vp) {
            // Valor já devolvido para este pagamento em todos os caixas que a venda já passou
            $jaDevolvidoPorPagamento = $vp->venda_pagamento_devolucao()->get()->sum('valor');

            // Valor disponível para devolução nessa forma de pagamento
            $valorDisponivel = $vp->valor - $jaDevolvidoPorPagamento;

            // Se não há sobra, interrompe a execução
            if ($sobra <= 0) {
                break;
            }

            $vendaPagamentoDevolucaoPorCaixa = $vp->venda_pagamento_devolucao()->where('caixa_evidencia_id', $this->request->getCaixaEvidenciaId())->first();
            if ($vendaPagamentoDevolucaoPorCaixa) {
                $sobra -=  $vendaPagamentoDevolucaoPorCaixa->valor;
            }
            // Valor a ser devolvido, respeitando o disponível e a sobra
            $valorProporcional = min($valorDisponivel, $sobra);

            if ($valorProporcional > 0) {

                if (!$vendaPagamentoDevolucaoPorCaixa) {

                    $vendaPagamentosDevolucao[] = DevolucaoRepository::criar_venda_pagamento_devolucao(
                        $vp->loja_id,
                        $vp->venda_id,
                        $this->request->getCaixaId(),
                        $this->request->getCaixaEvidenciaId(),
                        $devolucao->id,
                        $vp->id,
                        $valorProporcional,
                        $this->request->getCriarHistoricoRequest()
                    );
                } else {

                    $valorProporcionalAtualizado = ($valorProporcional + $vendaPagamentoDevolucaoPorCaixa->valor);

                    $vendaPagamentosDevolucao[] = DevolucaoRepository::atualiza_venda_pagamento_devolucao(
                        $vendaPagamentoDevolucaoPorCaixa->id,
                        $vp->loja_id,
                        $this->request->getCaixaId(),
                        $this->request->getCaixaEvidenciaId(),
                        $vp->venda_id,
                        $devolucao->id,
                        $vp->id,
                        $valorProporcionalAtualizado,
                        $this->request->getCriarHistoricoRequest()
                    );
                }

                if ($vp->especie->credito_loja) {
                    $this->retornaCreditoEmLoja($valorProporcional, $vp->venda->cliente_id);
                }

                // Subtrai o valor devolvido da sobra
                $sobra -= $valorProporcional;
            }
        }
        // dd($vendaPagamentosDevolucao);
        return $vendaPagamentosDevolucao;
    }

    private function calculaVendaPagamentosDevolucao()
    {
        $devolucoesItens = array_column($this->request->getItens(), null, 'vendaItemId');

        //pega somente os itens que foram devolvidos da vendaItem
        $vendaItens = VendaRepository::getVendaItemByIds($this->request->getLojaId(), array_keys($devolucoesItens));
        $valorTotalDevolvido = 0;
        $desconto = $vendaItens->first()->venda->desconto_porcentagem ?? 0;
        foreach ($vendaItens as $key => $item) {
            $valorTotalDevolvido += $item->preco * $devolucoesItens[$item->id]['quantidade_devolucao'];
        }

        $valorDesconto = ($desconto / 100) * $valorTotalDevolvido;
        $valorTotalDevolvido -= $valorDesconto;

        return round($valorTotalDevolvido);
    }

    private function criaDevolucaoItens(Devolucao $devolucao)
    {
        $devolucoesItens = array_column($this->request->getItens(), null, 'vendaItemId');

        //pega somente os itens que foram devolvidos da vendaItem
        $vendaItens = VendaRepository::getVendaItemByIds($this->request->getLojaId(), array_keys($devolucoesItens));
        $venda = $vendaItens->first()->venda;

        // Recupera os itens de devolução já existentes colocando a chave de venda_item_id como index
        // $devolucaoItensExistentes = $venda->devolucao_itens()->get();
        $devolucoesExistentesNaEvidenciaAtual = $venda->devolucao_itens()->where('caixa_evidencia_id', $this->request->getCaixaEvidenciaId())->get()->keyBy('venda_item_id');;

        $dataAtual = now();
        $itensDevolvidos = $vendaItens->map(function ($item) use ($devolucao, $devolucoesItens, $dataAtual, $devolucoesExistentesNaEvidenciaAtual) {
            $quantidadeDevolvida = $devolucoesItens[$item->id]['quantidade_devolucao']; //pega a quantidade devolvida
            $devolucaoItem = null;
            if ($devolucoesExistentesNaEvidenciaAtual->has($item->id)) {

                $devolucaoItemExistente = $devolucoesExistentesNaEvidenciaAtual->get($item->id);
                $quantidadeDevolvida = $devolucaoItemExistente->quantidade + $quantidadeDevolvida;

                // Calcula o valor bruto (antes do desconto)
                $valorBruto = $quantidadeDevolvida * $item->preco;

                // Aplica o desconto percentual
                $porcentagemDesconto = $devolucao->venda->desconto_porcentagem ?? 0; // Considera 0 se não houver desconto
                $valorDesconto = ($valorBruto * $porcentagemDesconto) / 100;

                // Calcula o valor final considerando o desconto
                $totalDevolvido = $valorBruto - $valorDesconto;

                $devolucaoItem = DevolucaoRepository::atualiza_devolucao_item(
                    $devolucaoItemExistente->id,
                    $devolucao->id,
                    $devolucao->loja_id,
                    $item->venda_id,
                    $this->request->getCaixaId(),
                    $this->request->getCaixaEvidenciaId(),
                    $item->id,
                    $item->estoque_id,
                    $item->estoque->id, //pega com query em loja logada
                    $item->produto_id,
                    $dataAtual,
                    $quantidadeDevolvida,
                    $item->preco,
                    $totalDevolvido,
                    $this->request->getCriarHistoricoRequest()
                );
            } else {
                // Calcula o valor bruto (antes do desconto)
                $valorBruto = $quantidadeDevolvida * $item->preco;

                // Aplica o desconto percentual
                $porcentagemDesconto = $devolucao->venda->desconto_porcentagem ?? 0; // Considera 0 se não houver desconto
                $valorDesconto = ($valorBruto * $porcentagemDesconto) / 100;

                // Calcula o valor final considerando o desconto
                $totalDevolvido = $valorBruto - $valorDesconto;
                $devolucaoItem = DevolucaoRepository::criar_devolucao_item(
                    $devolucao->id,
                    $devolucao->loja_id,
                    $item->venda_id,
                    $this->request->getCaixaId(),
                    $this->request->getCaixaEvidenciaId(),
                    $item->id,
                    $item->estoque_id,
                    $item->estoque->id, //pega com query em loja logada
                    $item->produto_id,
                    $dataAtual,
                    $quantidadeDevolvida,
                    $item->preco,
                    $totalDevolvido,
                    $this->request->getCriarHistoricoRequest()
                );
            }
            $devolucaoItem->quantidade_devolucao =$devolucoesItens[$item->id]['quantidade_devolucao'];
            return $devolucaoItem;
        });
        $this->movimentaEstoques($itensDevolvidos);
        return $itensDevolvidos;
    }


    private function movimentaEstoques($itensDevolvidos)
    {

        $movimentacao = MovimentacaoEstoqueApplication::criarMovimentacaoEstoque(new MovimentacaoEstoqueRequest(
            $this->request->getLojaId(),
            config('config.status.concluido'),
            $this->request->getCriarHistoricoRequest()->getUsuarioId(),
            config('config.tipo_movimentacao_estoque.devolucao'),
            $this->request->getCriarHistoricoRequest()
        ));

        foreach ($itensDevolvidos as $key => $item) {
            $estoqueId = $item->estoque_destino_id;
            $qtd = $item->quantidade_devolucao;

            MovimentacaoEstoqueApplication::movimentar(new MovimentacaoEstoqueItemRequest(
                $estoqueId,
                $movimentacao->id,
                config('config.tipo_movimentacao_estoque.devolucao'),
                $qtd,
                $this->request->getCriarHistoricoRequest()
            ));
        }

        return MovimentacaoEstoqueApplication::finalizarMovimentacao($movimentacao->id, $this->request->getCriarHistoricoRequest());
    }

    private function retornaCreditoEmLoja($valorRetornar, $clienteId)
    {
        $cliente = ClienteRepository::getClienteById($clienteId);
        $adicionarCredito = null;

        //caso entre aqui satisfaz todo o credito que o cliente tem consumido então zera ele 
        if ($cliente->credito->credito_loja_usado <= $valorRetornar) {
            $valorRetornar = 0;
            $adicionarCredito = ($cliente->credito->credito_loja_usado - $valorRetornar) + $cliente->credito->credito_loja;
     
        } else {
            $valorRetornar = $cliente->credito->credito_loja_usado - $valorRetornar;
        }

        return ClienteRepository::atualizar_credito_loja_usado($this->request->getCriarHistoricoRequest(), $clienteId, $valorRetornar, $adicionarCredito);
    }

    private function atualizaStatusCaixa()
    {
        return CaixaApplication::editar_status(new EditarStatusCaixaRequest($this->request->getCriarHistoricoRequest(), $this->request->getCaixaId(), config('config.status.livre'), $this->request->getCriarHistoricoRequest()->getUsuarioId()));
    }
}
