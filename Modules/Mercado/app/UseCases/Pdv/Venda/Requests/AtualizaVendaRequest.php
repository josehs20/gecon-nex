<?php

namespace Modules\Mercado\UseCases\Pdv\Venda\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class AtualizaVendaRequest
{
    private int $id;
    private CriarVendaRequest $criarVendaRequest;
    public function __construct(
        int $id,
        CriarVendaRequest $criarVendaRequest
    ) {
        $this->id = $id;
        $this->criarVendaRequest = $criarVendaRequest;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getCriarVendaRequest(): CriarVendaRequest
    {
        return $this->criarVendaRequest;
    }
}
