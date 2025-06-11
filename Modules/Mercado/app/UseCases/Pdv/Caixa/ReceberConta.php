<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Exception;
use Modules\Mercado\Application\ClienteApplication;
use Modules\Mercado\Application\PDVApplication;
use Modules\Mercado\Entities\FormaPagamento;
use Modules\Mercado\Repository\Caixa\CaixaRepository;
use Modules\Mercado\Repository\PDV\CaixaPDVRepository;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\CriarEvidenciaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\ReceberContaRequest;

class ReceberConta
{
    private ReceberContaRequest $request;
    public function __construct(ReceberContaRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $caixa = $this->validade();
        //cria evidencia
        $evidencia = $this->criarEvidencia();
        //abate falor pago em venda_parcelas
        $fichasCliente = $this->atualizaVendaParcela($evidencia);
        //voltaCreditoCliente
        $creditoCliente = $this->atualizaCreditoCliente($fichasCliente);
        //atualiza entrada no caixa
        $evidencia = $this->atualizaValoresEvidencia($evidencia, $fichasCliente);

        return collect($fichasCliente);
    }

    private function validade()
    {
        //nenhuma regra estabelecida ainda para validação
        $forma_mapagamento = FormaPagamento::find($this->request->getFormaPagamento());

        if ($forma_mapagamento->especie_pagamento_id == config('config.especie_pagamento.credito_loja.id')) {
            throw new Exception("Crédito em loja não permitido para recebimento.", 1);
        }
    }

    private function criarEvidencia()
    {
        return PDVApplication::criar_evidencias(new CriarEvidenciaRequest(
            $this->request->getCriarHistoricoRequest(),
            $this->request->getCaixaId(),
            $this->request->getCriarHistoricoRequest()->getAcaoId(),
            $this->request->getCriarHistoricoRequest()->getUsuarioId(),
            config('config.caixa.recursos.recebimento.id'),
            null,
            null,
            $this->request->getCriarHistoricoRequest()->getComentario()
        ));
    }

    private function atualizaVendaParcela($evidencia)
    {
        $vendaParcelas = [];
        $fichasCliente = [];
        $caixa = CaixaRepository::getCaixaById($this->request->getCaixaId());
        foreach ($this->request->getVendaParcelas() as $key => $vp) {
            $valor = converteExibicaoParaCentavos($vp['valor']);
            $vendaParcela = CaixaPDVRepository::getVendaParcelaById($vp['venda_parcela_id']);
            if (!$vendaParcela) {
                throw new Exception("Parcela não encontrada.", 1);
            }

            $totalPago = $valor + $vendaParcela->valor_pago;

            if ($totalPago > $vendaParcela->valor) {
                throw new Exception("O valor pago da parcela " . $vendaParcela->numero_parcela . ' é maior que o valor restante. Valor restante:' . converterParaReais(($vendaParcela->valor - $vendaParcela->valor_pago)) . '. Valor pago:' . converterParaReais($totalPago), 1);
            }
            $pago = false;
            $status_id = config('config.status.aberto');

            if ($totalPago >= $vendaParcela->valor) {
                $status_id = config('config.status.pago');
                $pago = true;
            }

            //atualiza venda parcelas com os valores já pago
            $vendaParcelas[] = CaixaPDVRepository::editaAttrsVendaParcela($this->request->getCriarHistoricoRequest(), $vendaParcela->id, [
                'valor_pago' => $totalPago,
                'status_id' => $status_id,
                'pago' => $pago,
                'data_pagamento' => $pago ? now() : null,
            ]);

            //cria uma ficha do que foi pago
            $fichasCliente[] = CaixaPDVRepository::criarFichaClienteAttrs($this->request->getCriarHistoricoRequest(), [
                'cliente_id' => $vendaParcela->cliente_id,
                'loja_id' => $this->request->getLojaId(),
                'venda_id' => $vendaParcela->venda_id,
                'valor' => $valor,
                'venda_pagamento_id' => $vendaParcela->venda_pagamento_id,
                'venda_parcela_id' => $vendaParcela->id,
                'caixa_diario_id' => $caixa->diario_atual->id,
                'caixa_evidencia_id' => $evidencia->id,
                'forma_pagamento_id' => $this->request->getFormaPagamento(),
            ]);
        }

        return $fichasCliente;
    }

    private function atualizaCreditoCliente($fichasCliente)
    {
        $fichas = collect($fichasCliente);
        $totalPago = $fichas->sum('valor');
        $cliente = $fichas->first()->cliente;
        $creditoUsado = $cliente->credito->credito_loja_usado;
        $novoCreditoUsado = $creditoUsado - $totalPago;

        return CaixaPDVRepository::editaClienteCreditoAttrs($this->request->getCriarHistoricoRequest(), $cliente->credito->id, [
            'credito_loja_usado' => $novoCreditoUsado,
        ]);
    }

    private function atualizaValoresEvidencia($evidencia, $fichasCliente)
    {
        $fichas = collect($fichasCliente);
        $totalPago = $fichas->sum('valor');
        $pagamentoEmDinheiro = false;
        if ($this->request->getFormaPagamento() == config('config.especie_pagamento.dinheiro.id')) {
            $pagamentoEmDinheiro = true;
        }

        $pagamentoEmDinheiro = $pagamentoEmDinheiro ? $totalPago : 0;
        $totais = $totalPago;
        $evidenciaAnterior = $evidencia->evidenciaAnterior();

        return CaixaPDVRepository::editaCaixaEvidenciaAttrs($this->request->getCriarHistoricoRequest(), $evidencia->id, [
            'valor_total' => $evidenciaAnterior->valor_total + $totais,
            'valor_dinheiro' => $evidenciaAnterior->valor_dinheiro + $pagamentoEmDinheiro,
            'total_credito_loja' => $evidenciaAnterior->total_credito_loja,
        ]);
    }
}
