<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Exception;
use Modules\Mercado\Application\PDVApplication;
use Modules\Mercado\Repository\Caixa\CaixaRepository;
use Modules\Mercado\Repository\PDV\CaixaPDVRepository;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\CriarEvidenciaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\FecharCaixaRequest;

class FecharCaixa
{
    private FecharCaixaRequest $request;

    public function __construct(FecharCaixaRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $ultima_evidencia = $this->validate();
        $evidencia = $this->criaEvidencia($ultima_evidencia);
        $fechamento = $this->criaFechamento($evidencia, $ultima_evidencia);
        $diario = $this->atualizaDiario($evidencia->caixa->diario_atual);
        $caixa = $this->fechaCaixa($evidencia->caixa);
        return $caixa;
    }

    private function validate()
    {
        $caixa = CaixaRepository::getCaixaById($this->request->getCaixaId());
        $valorEmDinheiroEsperado = $caixa->ultima_evidencia->valor_dinheiro;
        $valorInformado = $this->request->getValorDinheiro();

        // Calcula o valor mínimo permitido com 5% de tolerância
        $limiteMinimo = intval($valorEmDinheiroEsperado * 0.95);
        if ($valorInformado < $limiteMinimo) {
            throw new Exception(
                "Valor em dinheiro informado está abaixo do limite de tolerância (5%). Valor esperado: " .
                    converterParaReais($valorEmDinheiroEsperado) .
                    '. Valor mínimo permitido: ' . converterParaReais($limiteMinimo) .
                    '. Valor informado: ' . converterParaReais($valorInformado),
                1
            );
        }
        return $caixa->ultima_evidencia;
    }

    private function criaEvidencia($ultima_evidencia)
    {
        return PDVApplication::criar_evidencias(new CriarEvidenciaRequest(
            $this->request->getCriarHistoricoRequest(),
            $this->request->getCaixaId(),
            $this->request->getCriarHistoricoRequest()->getAcaoId(),
            $this->request->getCriarHistoricoRequest()->getUsuarioId(),
            config('config.caixa.recursos.fechamento.id'),
            $ultima_evidencia->valor_total,
            $ultima_evidencia->valor_dinheiro,
            $this->request->getCriarHistoricoRequest()->getComentario()
        ));
    }

    private function fechaCaixa($caixa)
    {
        return CaixaPDVRepository::editaAttrsCaixa($this->request->getCriarHistoricoRequest(), $caixa->id, [
            'status_id' => config('config.status.fechado'),
            'usuario_id' => null,
            'token' => null,
        ]);
    }

    private function atualizaDiario($caixaDiario)
    {
        return CaixaPDVRepository::editaAttrsCaixaDiario($this->request->getCriarHistoricoRequest(), $caixaDiario->id, [
            'status_id' => config('config.status.fechado'),
            'data_fechamento' => now(),
        ]);
    }

    private function criaFechamento($evidencia, $ultima_evidencia)
    {
        return CaixaPDVRepository::criarFechamentoAttrs(
            $this->request->getCriarHistoricoRequest(),
            [
                'caixa_id' => $this->request->getCaixaId(),
                'caixa_evidencia_id' => $evidencia->id,
                'usuario_id' => $this->request->getCriarHistoricoRequest()->getUsuarioId(),
                'valor_total' => $evidencia->valor_total,
                'valor_dinheiro' => $evidencia->valor_dinheiro,
                'valor_esperado_dinheiro' => $ultima_evidencia->valor_dinheiro,
                'motivo' => $this->request->getCriarHistoricoRequest()->getComentario(),
                'caixa_diario_id' => $evidencia->caixa->diario_atual->id,
            ]
        );
    }
}
