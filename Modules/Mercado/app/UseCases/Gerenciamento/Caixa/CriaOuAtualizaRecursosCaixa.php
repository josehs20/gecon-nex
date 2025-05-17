<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Caixa;

use Exception;
use Modules\Mercado\Repository\Caixa\CaixaRepository;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class CriaOuAtualizaRecursosCaixa
{
    private CriarHistoricoRequest $criarHistoricoRequest;
    private int $caixa;
    private array $recursos;

    public function __construct(array $recursos, int $caixa, CriarHistoricoRequest $criarHistoricoRequest)
    {
        $this->recursos = $recursos;
        $this->caixa = $caixa;
        $this->criarHistoricoRequest = $criarHistoricoRequest;
    }

    public function handle()
    {
        $this->validade();
        $caixa = self::atualizarRecursosCaixas();
        return $caixa;
    }

    public function validade()
    {
        $caixa = CaixaRepository::getCaixaById($this->caixa);
        if ($caixa->status_id != config('config.status.fechado')) {
            throw new Exception("O caixa precisa estar fechado para altera-lo. Status atual: " . $caixa->status->descricao(), 1);
        }
    }

    public function atualizarRecursosCaixas()
    {
        return CaixaRepository::atualizaRecursosCaixas(
            $this->caixa,
            $this->recursos,
            $this->criarHistoricoRequest
        );
    }
}
