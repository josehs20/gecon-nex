<?php

namespace Modules\Mercado\UseCases\Pedido\Cotacao\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class CriarCotacaoRequest extends ServiceUseCase
{
    private $loja_id;
    private $status_id;
    private $usuario_id;
    private $descricao;
    private $data_encerramento;

    public function __construct(
        CriarHistoricoRequest $criarHistoricoRequest,
        $loja_id,
        $status_id,
        $usuario_id,
        $data_encerramento = null,
        $descricao = null
    ) {
        parent::__construct($criarHistoricoRequest);
        $this->loja_id = $loja_id;
        $this->status_id = $status_id;
        $this->usuario_id = $usuario_id;
        $this->data_encerramento = $data_encerramento;
        $this->descricao = $descricao;
    }

    // Getter e Setter para loja_id
    public function getLojaId()
    {
        return $this->loja_id;
    }

    public function setLojaId($loja_id)
    {
        $this->loja_id = $loja_id;
    }

    // Getter e Setter para status_id
    public function getStatusId()
    {
        return $this->status_id;
    }

    public function setStatusId($status_id)
    {
        $this->status_id = $status_id;
    }

    // Getter e Setter para usuario_id
    public function getUsuarioId()
    {
        return $this->usuario_id;
    }

    public function setUsuarioId($usuario_id)
    {
        $this->usuario_id = $usuario_id;
    }

    // Getter e Setter para descricao
    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    // Getter e Setter para data_encerramento
    public function getDataEncerramento()
    {
        return $this->data_encerramento;
    }

    public function setDataEncerramento($data_encerramento)
    {
        $this->data_encerramento = $data_encerramento;
    }
}
