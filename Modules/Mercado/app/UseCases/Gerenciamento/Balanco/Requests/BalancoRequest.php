<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Balanco\Requests;

use Exception;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class BalancoRequest extends ServiceUseCase
{
    private int $loja_id;
    private int $status_id;
    private int $usuario_id;
    private $observacao;

    public function __construct(int $loja_id, int $status_id, int $usuario_id, $observacao = null, CriarHistoricoRequest $historicoRequest)
    {
        parent::__construct($historicoRequest);
        $this->loja_id = $loja_id;
        $this->status_id = $status_id;
        $this->usuario_id = $usuario_id;
        $this->observacao = $observacao;
    }

    public function getLojaId(): int
    {
        return $this->loja_id;
    }

    public function getObservacao()
    {
        return $this->observacao;
    }

    public function getStatusId(): int
    {
        return $this->status_id;
    }

    public function getUsuarioId(): int
    {
        return $this->usuario_id;
    }
}
