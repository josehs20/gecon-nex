<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Exception;
use Modules\Mercado\Repository\Caixa\CaixaRepository;
use Modules\Mercado\Repository\PDV\CaixaPDVRepository;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\SangriaRequest;

class Sangria
{
    private SangriaRequest $request;
    public function __construct(SangriaRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $caixa = $this->validade();

        $evidencia = $this->criaEvidencia($caixa);
        $sangria = $this->criaSangria($caixa, $evidencia);
        return $sangria;
    }

    private function validade()
    {
        $caixa = CaixaRepository::getCaixaById($this->request->getCaixaId());
        $evidenciaAtual = $caixa->ultima_evidencia;
        $valorEmDinheiroCaixa = $evidenciaAtual->valor_dinheiro;

        if ($this->request->getEspeciePagamentoId() == config('config.especie_pagamento.dinheiro.id')) {
            if ($this->request->getValorSangria() > $valorEmDinheiroCaixa) {
                //validação de diferença, a regra pode ser implementada aqui
                //por enquanto iremos adiantar essa parte finalizando o caixa a gente implementa isso
                throw new Exception("Valor em dinheiro em caixa menor que o solicitado, valor em caixa:" . converterParaReais($valorEmDinheiroCaixa), 1);
            }
        } else {
            //especie pagamento outros
            $totalTipoDiferenteDeDinheiro = $evidenciaAtual->valor_total - $valorEmDinheiroCaixa;
            if ($this->request->getValorSangria() > $totalTipoDiferenteDeDinheiro) {
                //validação de diferença, a regra pode ser implementada aqui
                //por enquanto iremos adiantar essa parte finalizando o caixa a gente implementa isso
                throw new Exception("Total em caixa diferente de dinheiro é menor que valor de sangria:" . converterParaReais($totalTipoDiferenteDeDinheiro), 1);
            }
        }

        return $caixa;
    }

    private function criaSangria($caixa, $evidencia)
    {
        return CaixaPDVRepository::criarSangriaAttrs($this->request->getCriarHistoricoRequest(), [
            'caixa_id' => $caixa->id,
            'caixa_evidencia_id' => $evidencia->id,
            'usuario_id' => $this->request->getCriarHistoricoRequest()->getUsuarioId(),
            'especie_pagamento_id' => $this->request->getEspeciePagamentoId(),
            'valor' => $this->request->getValorSangria(),
            'motivo' => $this->request->getCriarHistoricoRequest()->getComentario(),
            'caixa_diario_id' => $caixa->diario_atual->id
        ]);
    }

    private function criaEvidencia($caixa)
    {
        $evidenciaAtual = $caixa->ultima_evidencia;
        $valorSangriaEmDinheiro = $this->request->getEspeciePagamentoId() == config('config.especie_pagamento.dinheiro.id');
        $valorSangriaEmDinheiro = $valorSangriaEmDinheiro ? $this->request->getValorSangria() : 0;
        $valorTotal = $evidenciaAtual->valor_total - $this->request->getValorSangria();
        $valorDinheiro = $evidenciaAtual->valor_dinheiro - $valorSangriaEmDinheiro;

        return CaixaPDVRepository::criarCriarEvidenciaAttrs($this->request->getCriarHistoricoRequest(), [
            'caixa_id' => $caixa->id,
            'acao_id' => $this->request->getCriarHistoricoRequest()->getAcaoId(),
            'usuario_id' => $this->request->getCriarHistoricoRequest()->getUsuarioId(),
            'valor_total' => $valorTotal,
            'valor_dinheiro' => $valorDinheiro,
            'caixa_recurso_id' => config('config.caixa.recursos.sangria.id'),
            'descricao' => $this->request->getCriarHistoricoRequest()->getComentario(),
        ]);
    }
}
