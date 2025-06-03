<?php

namespace Modules\Mercado\UseCases\Pdv\Venda\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class CriarVendaRequest extends ServiceUseCase
{
    private $venda_id;
    private $caixa_id;
    private $loja_id;
    private $usuario_id;
    private $motivo;
    private $data_devolucao;
    private $total_devolvido;
    private $forma_pagamento_id;
    private $caixa_diario_id;

    public function __construct(
        $venda_id,
        $caixa_id,
        $loja_id,
        $usuario_id,
        $motivo,
        $data_devolucao,
        $total_devolvido,
        $forma_pagamento_id,
        $caixa_diario_id
    ) {
        $this->venda_id = $venda_id;
        $this->caixa_id = $caixa_id;
        $this->loja_id = $loja_id;
        $this->usuario_id = $usuario_id;
        $this->motivo = $motivo;
        $this->data_devolucao = $data_devolucao;
        $this->total_devolvido = $total_devolvido;
        $this->forma_pagamento_id = $forma_pagamento_id;
        $this->caixa_diario_id = $caixa_diario_id;
    }

    // GETTERS

    public function getVendaId()
    {
        return $this->venda_id;
    }

    public function getCaixaId()
    {
        return $this->caixa_id;
    }

    public function getLojaId()
    {
        return $this->loja_id;
    }

    public function getUsuarioId()
    {
        return $this->usuario_id;
    }

    public function getMotivo()
    {
        return $this->motivo;
    }

    public function getDataDevolucao()
    {
        return $this->data_devolucao;
    }

    public function getTotalDevolvido()
    {
        return $this->total_devolvido;
    }

    public function getFormaPagamentoId()
    {
        return $this->forma_pagamento_id;
    }

    public function getCaixaDiarioId()
    {
        return $this->caixa_diario_id;
    }

    // SETTERS

    public function setVendaId($venda_id)
    {
        $this->venda_id = $venda_id;
    }

    public function setCaixaId($caixa_id)
    {
        $this->caixa_id = $caixa_id;
    }

    public function setLojaId($loja_id)
    {
        $this->loja_id = $loja_id;
    }

    public function setUsuarioId($usuario_id)
    {
        $this->usuario_id = $usuario_id;
    }

    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;
    }

    public function setDataDevolucao($data_devolucao)
    {
        $this->data_devolucao = $data_devolucao;
    }

    public function setTotalDevolvido($total_devolvido)
    {
        $this->total_devolvido = $total_devolvido;
    }

    public function setFormaPagamentoId($forma_pagamento_id)
    {
        $this->forma_pagamento_id = $forma_pagamento_id;
    }

    public function setCaixaDiarioId($caixa_diario_id)
    {
        $this->caixa_diario_id = $caixa_diario_id;
    }
}
