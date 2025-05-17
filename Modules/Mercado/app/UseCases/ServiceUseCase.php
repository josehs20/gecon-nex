<?php

namespace Modules\Mercado\UseCases;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class ServiceUseCase
{
    private CriarHistoricoRequest $criarHistoricoRequest;

    // Construtor para inicializar a propriedade $criarHistoricoRequest
    public function __construct(CriarHistoricoRequest $criarHistoricoRequest)
    {
        $this->criarHistoricoRequest = $criarHistoricoRequest;
    }

    // Método 'get' para acessar o valor de $criarHistoricoRequest
    public function getCriarHistoricoRequest(): CriarHistoricoRequest
    {
        return $this->criarHistoricoRequest;
    }

    // Método 'set' para modificar o valor de $criarHistoricoRequest
    public function setCriarHistoricoRequest(CriarHistoricoRequest $criarHistoricoRequest): void
    {
        $this->criarHistoricoRequest = $criarHistoricoRequest;
    }
    
}
