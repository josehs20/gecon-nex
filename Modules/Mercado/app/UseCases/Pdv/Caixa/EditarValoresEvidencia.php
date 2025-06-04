<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Modules\Mercado\Repository\PDV\CaixaPDVRepository;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class EditarValoresEvidencia
{
    private CriarHistoricoRequest $criarHistorico;
    private int $evidenciaId;
    private int $total;
    private int $totalDinheiro;
    public function __construct(CriarHistoricoRequest $criarHistorico, int $evidenciaId, int $total, int $totalDinheiro)
    {
        $this->criarHistorico = $criarHistorico;
        $this->evidenciaId = $evidenciaId;
        $this->total = $total;
        $this->totalDinheiro = $totalDinheiro;
    }

    public function handle()
    {
        return $this->editarEvidencias();
    }

    private function editarEvidencias()
    {
        return CaixaPDVRepository::editaCaixaEvidenciaAttrs($this->criarHistorico, $this->evidenciaId, [
            'valor_total' => $this->total,
            'valor_dinheiro' => $this->totalDinheiro
        ]);
    }
}
