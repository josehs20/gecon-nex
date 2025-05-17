<?php

namespace Modules\Mercado\UseCases\Pedido\Pedido\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class CriarPedidoRequest extends ServiceUseCase
{
    private $loja_id;
    private $status_id;
    private $data_limite;
    private $observacao;
    private array $materiais;
    // Construtor
    public function __construct(
        CriarHistoricoRequest $historico,
        $loja_id,
        $status_id,
        $data_limite,
        $materiais,
        $observacao = null
    ) {
        parent::__construct($historico);
        $this->loja_id = $loja_id;
        $this->status_id = $status_id;
        $this->data_limite = $data_limite;
        $this->observacao = $observacao;
        $this->materiais = $materiais;
    }

    public function getMateriais()
    {
        return $this->materiais;
    }

    public function getLojaId()
    {
        return $this->loja_id;
    }

    public function getStatusId()
    {
        return $this->status_id;
    }

    public function getDataLimite()
    {
        return $this->data_limite;
    }

    public function getObservacao()
    {
        return $this->observacao;
    }


    public function setLojaId($loja_id)
    {
        $this->loja_id = $loja_id;
    }

    public function setStatusId($status_id)
    {
        $this->status_id = $status_id;
    }

    public function setDataLimite($data_limite)
    {
        $this->data_limite = $data_limite;
    }

    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;
    }
}
