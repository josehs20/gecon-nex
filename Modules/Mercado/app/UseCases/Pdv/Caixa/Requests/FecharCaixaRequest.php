<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class FecharCaixaRequest extends ServiceUseCase
{
    private int $caixa_id;
    private int $valorDinheiro;
    private bool $autorizado;

    // Construtor
    public function __construct(CriarHistoricoRequest $historicoRequest, int $caixa_id, int $valorDinheiro, bool $autorizado)
    {
        parent::__construct($historicoRequest);
        $this->caixa_id = $caixa_id;
        $this->valorDinheiro = $valorDinheiro;
        $this->autorizado = $autorizado;
    }

    public function getAutorizado(): bool
    {
        return $this->autorizado;
    }

    public function getValorDinheiro(): int
    {
        return $this->valorDinheiro;
    }
    // Getter e Setter para caixa_id
    public function getCaixaId(): int
    {
        return $this->caixa_id;
    }

    public function setCaixaId(int $caixa_id): void
    {
        $this->caixa_id = $caixa_id;
    }
}
