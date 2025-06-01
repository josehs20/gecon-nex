<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class CriarCaixaDiarioRequest extends ServiceUseCase
{
    private $caixa_id;
    private $caixa_evidencia_id;
    private $usuario_id;
    private $loja_id;
    private $status_id;
    private $data_abertura;
    private $data_fechamento;

    public function __construct(
        CriarHistoricoRequest $criarHistoricoRequest,
        $caixa_id,
        $caixa_evidencia_id,
        $usuario_id,
        $loja_id,
        $status_id,
        $data_abertura,
        $data_fechamento = null
    ) {
        parent::__construct($criarHistoricoRequest);
        $this->caixa_id = $caixa_id;
        $this->caixa_evidencia_id = $caixa_evidencia_id;
        $this->usuario_id = $usuario_id;
        $this->loja_id = $loja_id;
        $this->status_id = $status_id;
        $this->data_abertura = $data_abertura;
        $this->data_fechamento = $data_fechamento;
    }

    // Getters e Setters

    public function getCaixaId()
    {
        return $this->caixa_id;
    }

    public function setCaixaId($caixa_id)
    {
        $this->caixa_id = $caixa_id;
    }

    public function getCaixaEvidenciaId()
    {
        return $this->caixa_evidencia_id;
    }

    public function setCaixaEvidenciaId($caixa_evidencia_id)
    {
        $this->caixa_evidencia_id = $caixa_evidencia_id;
    }

    public function getUsuarioId()
    {
        return $this->usuario_id;
    }

    public function setUsuarioId($usuario_id)
    {
        $this->usuario_id = $usuario_id;
    }

    public function getLojaId()
    {
        return $this->loja_id;
    }

    public function setLojaId($loja_id)
    {
        $this->loja_id = $loja_id;
    }

    public function getStatusId()
    {
        return $this->status_id;
    }

    public function setStatusId($status_id)
    {
        $this->status_id = $status_id;
    }

    public function getDataAbertura()
    {
        return $this->data_abertura;
    }

    public function setDataAbertura($data_abertura)
    {
        $this->data_abertura = $data_abertura;
    }

    public function getDataFechamento()
    {
        return $this->data_fechamento;
    }

    public function setDataFechamento($data_fechamento)
    {
        $this->data_fechamento = $data_fechamento;
    }
}
