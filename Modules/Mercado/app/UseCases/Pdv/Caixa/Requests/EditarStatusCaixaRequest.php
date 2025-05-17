<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class EditarStatusCaixaRequest extends ServiceUseCase
{
    private int $id;
    private int $status_id;
    private int $usuario_id;

    public function __construct(CriarHistoricoRequest $historicoRequest, int $id, int $status_id, $usuario_id)
    {
        parent::__construct($historicoRequest);
        $this->id = $id;
        $this->status_id = $status_id;
        $this->usuario_id = $usuario_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getStatusId()
    {
        return $this->status_id;
    }

    public function setStatusId(int $status_id)
    {
        $this->status_id = $status_id;
    }

    public function getUsuarioId()
    {
        return $this->usuario_id;
    }

    public function settUsuarioId(int $usuario_id)
    {
        $this->usuario_id = $usuario_id;
    }
}
