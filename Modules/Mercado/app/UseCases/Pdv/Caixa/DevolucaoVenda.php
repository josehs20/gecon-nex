<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Exception;
use Modules\Mercado\Application\CaixaApplication;
use Modules\Mercado\Application\EstoqueApplication;
use Modules\Mercado\Application\MovimentacaoEstoqueApplication;
use Modules\Mercado\Application\PDVApplication;
use Modules\Mercado\Application\VendaApplication;
use Modules\Mercado\Entities\Devolucao;
use Modules\Mercado\Entities\DevolucaoItem;
use Modules\Mercado\Repository\Caixa\CaixaRepository;
use Modules\Mercado\Repository\Cliente\ClienteRepository;
use Modules\Mercado\Repository\Devolucao\DevolucaoRepository;
use Modules\Mercado\Repository\Estoque\EstoqueRepository;
use Modules\Mercado\Repository\PDV\CaixaPDVRepository;
use Modules\Mercado\Repository\Produto\ProdutoRepository;
use Modules\Mercado\Repository\Venda\VendaRepository;
use Modules\Mercado\UseCases\Gerenciamento\Estoque\Requests\CriarEstoqueRequest;
use Modules\Mercado\UseCases\Gerenciamento\Estoque\Requests\UpdateQtdDisponivelRequest;
use Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque\Requests\MovimentacaoEstoqueItemRequest;
use Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque\Requests\MovimentacaoEstoqueRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\CriarEvidenciaRequest;
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
            'valor_dinheiro' => $evidenciaAnterior->valor_dinheiro - $totalDevolvido,
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

        $estoqueIds = array_column($this->request->getItens(), 'estoqueId');
        $estoques = EstoqueRepository::getEstoqueByIds($estoqueIds);

        foreach ($this->request->getItens() as $key => $item) {
            $estoque = $estoques->first(function ($e) use ($item) {
                return $e->id == $item['estoqueId'];
            });

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
        $estoqueIds = array_column($this->request->getItens(), 'estoqueId');
        $venda = CaixaPDVRepository::getVendaById($this->request->getVendaId());
        $venda_itens = $venda->venda_itens;
        $total = 0; // Inicializa o total antes do loop

        foreach ($this->request->getItens() as $key => $item) {
            //regra de negocio decidir devolucao com valor atual ou valor de venda
            $vendaItem = $venda_itens->first(function ($v) use ($item) {
                return $v->estoque_id == $item['estoqueId'];
            });
            $valor = $vendaItem->preco * $item['quantidade'];
            $total += $valor; // Adiciona o valor do item ao total
        }

        return $total; // Retorna o total calculado
    }

    private function criaDevolucaoItens($devolucao, $evidencia)
    {
        $itens = $this->request->getItens();
        $venda_itens = $devolucao->venda->venda_itens;
        $devolucoesItens = [];
        foreach ($itens as $key => $i) {
            $vi = $venda_itens->first(function ($v) use ($i) {
                return $v->estoque_id == $i['estoqueId'];
            });
            $estoqueDestino = $vi->produto->estoques()->where('loja_id', $this->request->getLojaId())->first();

            if (!$estoqueDestino) {
                $estoqueDestino = EstoqueApplication::criarEstoque(new CriarEstoqueRequest(
                    $vi->estoque->custo,
                    $vi->estoque->preco,
                    $vi->estoque->produto_id,
                    $this->request->getLojaId(),
                    $i['quantidade'],
                    $i['quantidade'],
                    null,
                    null,
                    null,
                    $this->request->getCriarHistoricoRequest()
                ));
            } else {
                $qtdTotal = $estoqueDestino->quantidade_total + $i['quantidade'];
                $qtdDisponivel = $estoqueDestino->quantidade_disponivel + $i['quantidade'];
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
                'quantidade' => $i['quantidade'],
                'preco' => $vi->preco,
                'total' => $vi->preco * $i['quantidade']
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
