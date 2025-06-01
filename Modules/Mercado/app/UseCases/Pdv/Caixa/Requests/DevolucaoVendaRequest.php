<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class DevolucaoVendaRequest extends ServiceUseCase
{
    private $venda_id;
    private $caixa_id;
    private $loja_id;
    private $usuario_id;
    private $forma_pagamento_id;

    public function __construct(CriarHistoricoRequest $criarHistoricoRequest,$venda_id, $caixa_id, $loja_id, $usuario_id, $forma_pagamento_id)
    {
        parent::__construct($criarHistoricoRequest);
        $this->venda_id = $venda_id;
        $this->caixa_id = $caixa_id;
        $this->loja_id = $loja_id;
        $this->usuario_id = $usuario_id;
        $this->forma_pagamento_id = $forma_pagamento_id;
    }

    public function getVendaId()
    {
        return $this->venda_id;
    }

    public function setVendaId($venda_id)
    {
        $this->venda_id = $venda_id;
    }

    public function getCaixaId()
    {
        return $this->caixa_id;
    }

    public function setCaixaId($caixa_id)
    {
        $this->caixa_id = $caixa_id;
    }

    public function getLojaId()
    {
        return $this->loja_id;
    }

    public function setLojaId($loja_id)
    {
        $this->loja_id = $loja_id;
    }

    public function getUsuarioId()
    {
        return $this->usuario_id;
    }

    public function setUsuarioId($usuario_id)
    {
        $this->usuario_id = $usuario_id;
    }

    public function getFormaPagamentoId()
    {
        return $this->forma_pagamento_id;
    }

    public function setFormaPagamentoId($forma_pagamento_id)
    {
        $this->forma_pagamento_id = $forma_pagamento_id;
    }
}
