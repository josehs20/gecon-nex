<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Exception;
use Modules\Mercado\Application\ClienteApplication;
use Modules\Mercado\Application\PDVApplication;
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
        $this->atualizaCreditoCliente($fichasCliente);
        //atualiza entrada no caixa
        $this->atualizaValoresEvidencia();

        return $caixa;
    }

    private function validade()
    {
        //nenhuma regra estabelecida ainda para validação
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
                throw new Exception("O valor pago da parcela " . $vendaParcela->parcela . ' é maior que o valor restante. Valor restante:' . converterParaReais(($vendaParcela->valor - $vendaParcela->valor_pago)), 1);
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
            ]);
           
            //cria uma ficha do que foi pago
            $fichasCliente[] = CaixaPDVRepository::criarFichaClienteAttrs($this->request->getCriarHistoricoRequest(), $vendaParcela->id, [
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
        $valorTotalPago = dd($fichasCliente);
        $creditoAve = ClienteApplication::getClienteById();
    }

    private function atualizaValoresEvidencia($evidencia) {}
}
