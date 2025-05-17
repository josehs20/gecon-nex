<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa\Requests;

use Modules\Mercado\UseCases\Pdv\Venda\Requests\CriarVendaRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class FinalizarVendaRequest extends ServiceUseCase
{
    private CriarVendaRequest $vendaRequest;

    // Construtor para inicializar os campos
    public function __construct(CriarVendaRequest $vendaRequest)
    {
        $this->vendaRequest = $vendaRequest;
    }

    // Método get para $vendaRequest
    public function getVendaRequest(): CriarVendaRequest
    {
        return $this->vendaRequest;
    }

    // Método set para $vendaRequest
    public function setVendaRequest(CriarVendaRequest $vendaRequest): void
    {
        $this->vendaRequest = $vendaRequest;
    }
}
