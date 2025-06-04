<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class SuprirCaixaRequest extends ServiceUseCase
{
    protected $caixa_id;
    protected $caixa_evidencia_id;
    protected $caixa_diario_id;
    protected $usuario_id;
    protected $valor;
    protected $motivo;
    protected $especie_pagamento_id;

    public function __construct(
        CriarHistoricoRequest $historicoRequest,
        $caixa_id,
        $caixa_evidencia_id,
        $caixa_diario_id,
        $usuario_id,
        $valor,
        $especie_pagamento_id,
        $motivo
    ) {
        parent::__construct($historicoRequest);

        $this->caixa_id = $caixa_id;
        $this->especie_pagamento_id = $especie_pagamento_id;
        $this->caixa_evidencia_id = $caixa_evidencia_id;
        $this->caixa_diario_id = $caixa_diario_id;
        $this->usuario_id = $usuario_id;
        $this->valor = $valor;
        $this->motivo = $motivo;
    }

     public function getEspeciePagamentoId()
    {
        return $this->caixa_id;
    }
    // Getter e Setter para caixa_id
    public function getCaixaId()
    {
        return $this->caixa_id;
    }

    public function setCaixaId($caixa_id)
    {
        $this->caixa_id = $caixa_id;
    }

    // Getter e Setter para caixa_evidencia_id
    public function getCaixaEvidenciaId()
    {
        return $this->caixa_evidencia_id;
    }

    public function setCaixaEvidenciaId($caixa_evidencia_id)
    {
        $this->caixa_evidencia_id = $caixa_evidencia_id;
    }

    // Getter e Setter para caixa_diario_id
    public function getCaixaDiarioId()
    {
        return $this->caixa_diario_id;
    }

    public function setCaixaDiarioId($caixa_diario_id)
    {
        $this->caixa_diario_id = $caixa_diario_id;
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

    // Getter e Setter para valor
    public function getValor()
    {
        return $this->valor;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    // Getter e Setter para motivo
    public function getMotivo()
    {
        return $this->motivo;
    }

    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;
    }
}
