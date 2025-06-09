<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Exception;
use Modules\Mercado\Application\EstoqueApplication;
use Modules\Mercado\Application\MovimentacaoEstoqueApplication;
use Modules\Mercado\Application\PDVApplication;
use Modules\Mercado\Repository\Cliente\ClienteRepository;
use Modules\Mercado\Repository\PDV\CaixaPDVRepository;
use Modules\Mercado\Repository\Venda\VendaRepository;
use Modules\Mercado\UseCases\Gerenciamento\Estoque\Requests\CriarEstoqueRequest;
use Modules\Mercado\UseCases\Gerenciamento\Estoque\Requests\UpdateQtdDisponivelRequest;
use Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque\Requests\MovimentacaoEstoqueItemRequest;
use Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque\Requests\MovimentacaoEstoqueRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\CriarEvidenciaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\DevolucaoVendaRequest;

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
        $evidencia = $this->criaEvidencia();
        $devolucao = $this->criaDevolucao($evidencia);
        $itensDevolvidos = $this->criaDevolucaoItens($devolucao, $evidencia);
        $this->movimentaEstoques($itensDevolvidos);
        $this->atualizaValoresEvidencia($evidencia, $devolucao);
        $this->atualizaStatusCaixa();
        return $devolucao;
    }

    private function criaEvidencia()
    {
        return PDVApplication::criar_evidencias(new CriarEvidenciaRequest(
            $this->request->getCriarHistoricoRequest(),
            $this->request->getCaixaId(),
            $this->request->getCriarHistoricoRequest()->getAcaoId(),
            $this->request->getUsuarioId(),
            config('config.caixa.recursos.devolucao.id')
        ));
    }

    private function atualizaValoresEvidencia($evidencia, $devolucao)
    {
        $devolucaoEmDinheiro = $devolucao->formaPagamento->especie_pagamento_id == config('config.especie_pagamento.dinheiro.id');

        $devolucaoEmDinheiro = $devolucaoEmDinheiro ? $devolucao->total_devolvido : 0;
        $totalDevolvido = $devolucao->total_devolvido;
        $evidenciaAnterior = $evidencia->evidenciaAnterior();

        return CaixaPDVRepository::editaCaixaEvidenciaAttrs($this->request->getCriarHistoricoRequest(), $evidencia->id, [
            'valor_total' => $evidenciaAnterior->valor_total - $totalDevolvido,
            'valor_dinheiro' => $evidenciaAnterior->valor_dinheiro - $devolucaoEmDinheiro,
        ]);
    }

    private function validate()
    {
        $itens = [];
        if ($this->request->getItens() == 0) {
            throw new Exception("Nenhum item foi selecionado para devolução", 1);
        }


        if (!$this->request->getCriarHistoricoRequest()->getComentario()) {
            throw new Exception("Informe o motivo", 1);
        }

        $vendaItensDevolvidos = array_column($this->request->getItens(), 'venda_item_id');
        // $estoqueIds = array_column($this->request->getItens(), 'estoqueId');
        // $estoques = EstoqueRepository::getEstoqueByIds($estoqueIds);
        $vendaItensDevolvidos = VendaRepository::getVendaItemByIds($this->request->getLojaId(), $vendaItensDevolvidos);

        foreach ($this->request->getItens() as $key => $item) {
            $estoque = $vendaItensDevolvidos->first(function ($vi) use ($item) {
                return $vi->id == $item['venda_item_id'];
            })->estoque;

            $valor = $item['quantidade'];
            $isFloat = intval($valor) != $valor;

            if (!$estoque->produto->unidade_medida->pode_ser_float && $isFloat) {
                throw new Exception("O valor do material " . $estoque->produto->nome . 'não pode ser um valor fracionado. Unidade de medida: ' . $estoque->produto->unidade_medida->sigla, 1);
            }
        }
    }

    private function criaDevolucao($evidencia)
    {
        $totalDevolvido = $this->calculaValoresDevolucao();
        return CaixaPDVRepository::criarDevolucaoAttrs($this->request->getCriarHistoricoRequest(), [
            'venda_id' => $this->request->getVendaId(),
            'caixa_id' => $this->request->getCaixaId(),
            'loja_id' => $this->request->getLojaId(),
            'usuario_id' => $this->request->getUsuarioId(),
            'motivo' => $this->request->getCriarHistoricoRequest()->getComentario(),
            'data_devolucao' => now(),
            'total_devolvido' => $totalDevolvido,
            'forma_pagamento_id' => $this->request->getFormaPagamentoId(),
            'caixa_diario_id' => $evidencia->caixa->diario_atual->id,
            'caixa_evidencia_id' => $evidencia->id,
        ]);
    }

    private function calculaValoresDevolucao()
    {
        $venda = CaixaPDVRepository::getVendaById($this->request->getVendaId());
        $venda_itens = $venda->venda_itens;
        $total = 0; // Inicializa o total antes do loop

        foreach ($this->request->getItens() as $key => $item) {
            //regra de negocio decidir devolucao com valor atual ou valor de venda
            $vendaItem = $venda_itens->first(function ($v) use ($item) {
                return $v->id == $item['venda_item_id'];
            });
            // Converte a string de quantidade para um formato numérico com ponto decimal
            $quantidadeNumerica = formatarQtdRequest($item['quantidade']);

            $valor = $vendaItem->preco * $quantidadeNumerica; // Use a quantidade numérica
            $total += $valor; // Adiciona o valor do item ao total
        }

        $desconto_porcentagem = $venda->desconto_porcentagem ?? 0;

        // Calcula o valor do desconto em reais
        $valor_desconto = $total * ($desconto_porcentagem / 100);

        // Subtrai o valor do desconto do total
        $total_com_desconto = $total - $valor_desconto;

        // Opcional: Se você quer que o retorno seja o total já com desconto
        return round($total_com_desconto, 0); //arredonda tranformando em inteiro para centavos
    }

    private function criaDevolucaoItens($devolucao, $evidencia)
    {
        $itens = $this->request->getItens();
        $venda_itens = $devolucao->venda->venda_itens;
        $devolucoesItens = [];
        foreach ($itens as $key => $i) {
            $vi = $venda_itens->first(function ($v) use ($i) {
                return $v->id == $i['venda_item_id'];
            });
            $estoqueDestino = $vi->produto->estoques()->where('loja_id', $this->request->getLojaId())->first();
            $qtdRequest = formatarQtdRequest($i['quantidade']);

            if (!$estoqueDestino) {
                $estoqueDestino = EstoqueApplication::criarEstoque(new CriarEstoqueRequest(
                    $vi->estoque->custo,
                    $vi->estoque->preco,
                    $vi->estoque->produto_id,
                    $this->request->getLojaId(),
                    $qtdRequest,
                    $qtdRequest,
                    null,
                    null,
                    null,
                    $this->request->getCriarHistoricoRequest()
                ));
            } else {
                $qtdTotal = $estoqueDestino->quantidade_total + $qtdRequest;
                $qtdDisponivel = $estoqueDestino->quantidade_disponivel + $qtdRequest;
                $estoqueDestino = EstoqueApplication::updateQtdDisponivel(new UpdateQtdDisponivelRequest($estoqueDestino->id, $qtdDisponivel, $qtdTotal, $this->request->getCriarHistoricoRequest()));
            }

            $devolucoesItens[] = CaixaPDVRepository::criarDevolucaoItensAttrs($this->request->getCriarHistoricoRequest(), [
                'devolucao_id' => $devolucao->id,
                'loja_id' => $devolucao->loja_id,
                'venda_id' => $devolucao->venda_id,
                'caixa_id' => $devolucao->caixa_id,
                'caixa_evidencia_id' => $evidencia->id,
                'venda_item_id' => $vi->id,
                'estoque_origem_id' => $vi->estoque_id,
                'estoque_destino_id' => $estoqueDestino->id,
                'produto_id' => $vi->produto_id,
                'data_devolucao' => now(),
                'quantidade' => $qtdRequest,
                'preco' => $vi->preco,
                'total' => $vi->preco * $qtdRequest
            ]);
        }
        return $devolucoesItens;
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
            $qtd = $item->quantidade;

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
        return CaixaPDVRepository::editaAttrsCaixa($this->request->getCriarHistoricoRequest(), $this->request->getCaixaId(), [
            'status_id' => config('config.status.livre')
        ]);
    }
}
