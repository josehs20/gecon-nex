<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Exception;
use Modules\Mercado\Application\CaixaApplication;
use Modules\Mercado\Application\MovimentacaoEstoqueApplication;
use Modules\Mercado\Application\PDVApplication;
use Modules\Mercado\Application\VendaApplication;
use Modules\Mercado\Entities\Venda;
use Modules\Mercado\Repository\Caixa\CaixaRepository;
use Modules\Mercado\Repository\Pagamento\PagamentoRepository;
use Modules\Mercado\Repository\PDV\CaixaPDVRepository;
use Modules\Mercado\Repository\Venda\VendaRepository;
use Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque\Requests\MovimentacaoEstoqueItemRequest;
use Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque\Requests\MovimentacaoEstoqueRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\CriarEvidenciaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\CriarVendaPagamentoRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\EditarStatusCaixaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\FinalizarVendaRequest;
use Modules\Mercado\UseCases\Pdv\Venda\Requests\AtualizaVendaRequest;

class FinalizarVenda
{
    private FinalizarVendaRequest $request;

    public function __construct(FinalizarVendaRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $itensTemp = $this->validate();
        $evidencia = $this->criaEvidencia();
        $venda = $this->criaVenda($evidencia);
        $vendaItens = $this->criaVendaItens($venda, $itensTemp);
        $pagamentos = $this->criaVendaPagamentos($venda);
        $fichaCliente = $this->criaFichaCliente($venda);
        $movimentacaoEstoque = $this->movimentaEstoques($venda);
        $evidencia = $this->atualizaTotaisEvidencia($evidencia, $venda);
        $caixa = $this->updateCaixa();
        $this->limpaCaixaItensTemp();

        return $venda;
    }

    private function validate()
    {
        $itens = CaixaPDVRepository::getItensCaixaTemp($this->request->getCaixaId());
        if ($itens->count() == 0) {
            throw new Exception("Não existe itens na venda.", 1);
        }

        return $itens;
    }

    private function criaEvidencia()
    {
        return PDVApplication::criar_evidencias(new CriarEvidenciaRequest(
            $this->request->getCriarHistoricoRequest(),
            $this->request->getCaixaId(),
            $this->request->getCriarHistoricoRequest()->getAcaoId(),
            $this->request->getCriarHistoricoRequest()->getUsuarioId(),
            config('config.caixa.recursos.venda.id'),
        ));
    }

    private function criaVenda($evidencia)
    {
        $valores = PDVApplication::calculaTotaisVendaTemp($this->request->getCaixaId(), $this->request->getDesconto());
        $total_pago = array_sum(array_column($this->request->getFormasPagamento(), 'valor'));

        if ($total_pago < $valores['total']) {
            throw new Exception("O valor recebido é menor que o valor total da venda.", 1);
        }

        return CaixaPDVRepository::criarVendaAttrs($this->request->getCriarHistoricoRequest(), [
            'n_venda' => PDVApplication::gerarNumeroVenda($evidencia->caixa->loja_id),
            'cliente_id' => $this->request->getClienteId(),
            'caixa_id' => $this->request->getCaixaId(),
            'caixa_evidencia_id' => $evidencia->id,
            'caixa_diario_id' => $evidencia->caixa->diario_atual->id,
            'loja_id' => $evidencia->caixa->loja_id,
            'usuario_id' => $this->request->getCriarHistoricoRequest()->getUsuarioId(),
            'status_id' => config('config.status.concluido'),
            'sub_total' => $valores['sub_total'],
            'total' => $valores['total'],
            'desconto_porcentagem' => $valores['desconto_percentual'],
            'desconto_dinheiro' => $valores['desconto_centavos'],
        ]);
    }

    private function criaVendaItens($venda, $itensTemp)
    {
        foreach ($itensTemp as $key => $i) {
            CaixaPDVRepository::criarVendaItemAttrs($this->request->getCriarHistoricoRequest(), [
                'venda_id' => $venda->id,
                'caixa_id' => $venda->caixa_id,
                'caixa_evidencia_id' => $venda->caixa_evidencia_id,
                'caixa_diario_id' => $venda->caixa->diario_atual->id,
                'estoque_id' => $i->estoque_id,
                'loja_id' => $venda->loja_id,
                'produto_id' => $i->produto_id,
                'quantidade' => $i->quantidade,
                'preco' => $i->preco,
                'total' => $i->total
            ]);
        }
    }

    private function criaVendaPagamentos(Venda $venda)
    {
        $pagamentos = [];
        foreach ($this->request->getFormasPagamento() as $key => $fp) {
            $valor = (int) $fp['valor']; // Sempre centavos
            $parcelas = isset($fp['parcelas']) ? (int) $fp['parcelas'] : 1;

            $valorParcela = intdiv($valor, $parcelas); // divisão inteira
            $resto = $valor % $parcelas; // ajuste para não perder centavos

            $parcelasValores = [];
            //cria a venda pagamentos sendo eles os tipos de pagamentos que foram efetuados
            $formaPagamento = PagamentoRepository::getFormaPagamentoById($fp['id']);
            $vendaPagamento = CaixaPDVRepository::criarVendaPagamentoAttrs($this->request->getCriarHistoricoRequest(), [
                'venda_id' => $venda->id,
                'forma_pagamento_id' => $formaPagamento->id,
                'especie_pagamento_id' => $formaPagamento->especie_pagamento_id,
                'loja_id' => $venda->loja_id,
                'valor' => $valor,
                'cliente_id' => $this->request->getClienteId(),
                'parcelado' => $parcelas != 1,
                'quantidade_parcelas' => $parcelas,
                'caixa_diario_id' => $venda->caixa->diario_atual->id,
            ]);

            for ($i = 0; $i < $parcelas; $i++) {
                $valorAtual = $valorParcela;
                // Distribui o resto para as primeiras parcelas
                if ($i < $resto) {
                    $valorAtual += 1;
                }
                $parcelasValores[] = [
                    'forma_pagamento_id' => $fp['id'],
                    'parcela' => $i + 1,
                    'valor' => $valorAtual
                ];
            }

            //agora cria de fato o que foi pago e as parcelas de cada forma de pagamento
            foreach ($parcelasValores as $key => $pv) {
                CaixaPDVRepository::criarVendaParcelaAttrs($this->request->getCriarHistoricoRequest(), [
                    'venda_id' => $venda->id,
                    'loja_id' => $venda->loja_id,
                    'venda_pagamento_id' => $vendaPagamento->id,
                    'numero_parcela' => $pv['parcela'],
                    'valor' => $pv['valor'],
                    'data_vencimento' => now(),
                    'data_pagamento' => now(),
                    'pago' => true,
                    'forma_pagamento_id' => $vendaPagamento->forma_pagamento_id,
                    'cliente_id' => $this->request->getClienteId(),
                    'status_id' => config('config.status.pago'),
                    'caixa_diario_id' => $venda->caixa->diario_atual->id,
                ]);
            }
            $pagamentos[] = $vendaPagamento;
        }
        return $pagamentos;
    }

    private function criaFichaCliente(Venda $venda)
    {
        return $venda->venda_pagamentos->map(function ($vp) {
            return $vp->vendaParcelas->map(function ($v) {
                return CaixaPDVRepository::criarFichaClienteAttrs($this->request->getCriarHistoricoRequest(), [
                    'cliente_id' => $v->cliente_id,
                    'loja_id' => $v->loja_id,
                    'venda_id' => $v->venda_id,
                    'valor' => $v->valor,
                    'venda_pagamento_id' => $v->venda_pagamento_id,
                    'venda_parcela_id' => $v->id,
                    'caixa_diario_id' => $v->venda->caixa->diario_atual->id,
                ]);
            });
        });
    }

    private function movimentaEstoques(Venda $venda)
    {
        $vendaItens = $venda->venda_itens;

        $movimentacao = MovimentacaoEstoqueApplication::criarMovimentacaoEstoque(new MovimentacaoEstoqueRequest(
            $venda->loja_id,
            $venda->status_id,
            $this->request->getCriarHistoricoRequest()->getUsuarioId(),
            config('config.tipo_movimentacao_estoque.venda'),
            $this->request->getCriarHistoricoRequest()
        ));

        foreach ($vendaItens as $key => $item) {
            $estoqueId = $item->estoque_id;
            $qtd = $item->quantidade;

            MovimentacaoEstoqueApplication::movimentar(new MovimentacaoEstoqueItemRequest(
                $estoqueId,
                $movimentacao->id,
                config('config.tipo_movimentacao_estoque.venda'),
                $qtd,
                $this->request->getCriarHistoricoRequest()
            ));
        }

        return MovimentacaoEstoqueApplication::finalizarMovimentacao($movimentacao->id, $this->request->getCriarHistoricoRequest());
    }

    private function atualizaTotaisEvidencia($evidencia, $venda)
    {
        $pagamentoEmDinheiro = $venda->venda_pagamentos->first(function ($item) {
            return $item->especie_pagamento_id == config('config.especie_pagamento.dinheiro.id');
        });

        $pagamentoEmDinheiro = $pagamentoEmDinheiro ? $pagamentoEmDinheiro->valor : 0;
        $totais = $venda->venda_pagamentos->sum('valor');
        $evidenciaAnterior = $evidencia->evidenciaAnterior();

        return CaixaPDVRepository::editaCaixaEvidenciaAttrs($this->request->getCriarHistoricoRequest(), $evidencia->id, [
            'valor_total' => $evidenciaAnterior->valor_total + $totais,
            'valor_dinheiro' => $evidenciaAnterior->valor_dinheiro + $pagamentoEmDinheiro,
        ]);
    }

    private function updateCaixa()
    {
        $statusId = config('config.status.livre');
        return CaixaPDVRepository::editaAttrsCaixa($this->request->getCriarHistoricoRequest(), $this->request->getCaixaId(), [
            'status_id' => $statusId
        ]);
    }

    private function limpaCaixaItensTemp()
    {
        CaixaPDVRepository::limpaCaixaItensTemp($this->request->getCaixaId());
    }
}
