<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Pagamento;

use Exception;
use Modules\Mercado\Repository\Cliente\ClienteRepository;
use Modules\Mercado\Repository\Pagamento\PagamentoRepository;
use Modules\Mercado\UseCases\Gerenciamento\Pagamento\Requests\ReceberVendaRequest;

class ReceberVenda
{
    private ReceberVendaRequest $request;

    public function __construct(ReceberVendaRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        return $this->checarVendaPagamento($this->realizaRecebimento($this->montaPagamentos($this->validate())));
    }

    private function validate()
    {
        $vendaPagamentos = collect();
        foreach ($this->request->getVendaPagamentos() as $key => $vp) {
            $vPagamento = PagamentoRepository::getVendaPagamentoById($vp['venda_pagamento_id']);
            $totalDevolucao = $vPagamento->venda_pagamento_devolucao->sum('valor');
            $restaReceber = ($vPagamento->valor - $totalDevolucao) - $vPagamento->valor_pago;
            $recebido = converteDinheiroParaFloat($vp['valor']);

            if ($recebido > $restaReceber) {
                throw new Exception("O valor recebido na venda " . $vPagamento->venda->n_venda . ' é maior do que resta para receber.', 1);
            }

            $vPagamento->valor_receber = $recebido;
            $vPagamento->valor_receber_total = $recebido;
            $vPagamento->pendente = $restaReceber;
            $vendaPagamentos->push($vPagamento);
        }

        return $vendaPagamentos;
    }

    private function montaPagamentos($vendaPagamentos)
    {
        foreach ($this->request->getFormaPagamentos() as $key => $fp) {
            $fp = (object) $fp;
            $valorPago = converteDinheiroParaFloat($fp->valor);
            $especieId = PagamentoRepository::getFormaPagamentoById($fp->forma_pagamento_id)->especie_pagamento_id;
            foreach ($vendaPagamentos as $key => $vp) {
                $formasRecebimento = $vp->formas_recebimento ?? [];

                if ($valorPago != 0) {

                    if ($valorPago >= $vp->valor_receber) {
                        $formasRecebimento[] = [
                            'forma_pagamento' => $fp->forma_pagamento_id,
                            'valor_pagamento' => $vp->valor_receber,
                            'parcelas' => $fp->parcelas == 0 ? null : intval($fp->parcelas),
                            'especie' => $especieId,
                        ];
                        $valorPago -= $vp->valor_receber;
                        $vp->valor_receber = 0;
                    } else {
                        $formasRecebimento[] = [
                            'forma_pagamento' => $fp->forma_pagamento_id,
                            'valor_pagamento' => $valorPago,
                            'parcelas' => $fp->parcelas == 0 ? null : intval($fp->parcelas),
                            'especie' => $especieId,
                        ];
                        $vp->valor_receber -= $valorPago;
                        $valorPago = 0;
                    }
                }

                $vp->formas_recebimento = $formasRecebimento;
            }
        }
      
        return $vendaPagamentos;
    }

    private function realizaRecebimento($vendaPagamentos)
    {
        $pagamentos = [];
        foreach ($vendaPagamentos as $key => $vp) {
            foreach ($vp->formas_recebimento as $key => $f) {
                $f = (object) $f;

                $pagamentos[] = PagamentoRepository::criaPagamento(
                    $this->request->getCriarHistoricoRequest(),
                    $this->request->getLojaId(),
                    $this->request->getCaixaId(),
                    $this->request->getCaixaEvidenciaId(),
                    $vp->venda_id,
                    $vp->id,
                    $f->forma_pagamento,
                    $f->especie,
                    $f->valor_pagamento,
                    now(),
                    $f->parcelas
                );
            }
        }
        $this->retornaCreditoEmLoja($pagamentos);
        return $pagamentos;
    }

    private function checarVendaPagamento($pagamentos)
    {
        $pagamentos = collect($pagamentos);
        $vendaPagamentos = $pagamentos->groupBy('venda_pagamento_id')->map(function ($grupo) {
            $vPagamento = $grupo->first()->venda_pagamento;
            $vPagamento->total_valor_pago = $grupo->sum('valor') + $vPagamento->valor_pago;
            return $vPagamento;
        });
     
        foreach ($vendaPagamentos as $key => $vp) {
            $vPagamento = PagamentoRepository::getVendaPagamentoById($vp->id);
            $totalDevolucao = $vPagamento->venda_pagamento_devolucao->sum('valor');
            $restaReceber = ($vPagamento->valor - $totalDevolucao);
            $status_id = null;
            if ($restaReceber == $vp->total_valor_pago) {
                $status_id = config('config.status.pago');
            }
            
            PagamentoRepository::atualizaValorPago($this->request->getCriarHistoricoRequest(), $vp->id, $vp->total_valor_pago, $status_id);
        }

        return $pagamentos;
    }

    private function retornaCreditoEmLoja($pagamentos)
    {
        $totalRecebido = collect($pagamentos)->sum('valor');
        $cliente = ClienteRepository::getClienteById(collect($pagamentos)->first()->venda->cliente_id);

        //caso entre aqui satisfaz todo o credito que o cliente tem consumido então zera ele 
        if ($cliente->credito->credito_loja_usado <= $totalRecebido) {
            $valorRetornar = 0;
        } else {
            $valorRetornar = $cliente->credito->credito_loja_usado - $totalRecebido;
        }

        return ClienteRepository::atualizar_credito_loja_usado($this->request->getCriarHistoricoRequest(), $cliente->id, $valorRetornar);
    }
}
