<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class OrcamentoRequest extends ServiceUseCase
{
    private $cliente_id;
    private $loja_id;
    private $usuario_id;
    private $status_id;
    private $empresa_master_cod;
    private $caixa_id;
    private $forma_pagamento_id;
    private $desconto_porcentagem;
    private $descricao;

    public function __construct(
        CriarHistoricoRequest $criarHistoricoRequest,
        $loja_id,
        $usuario_id,
        $empresa_master_cod,
        $caixa_id,
        $status_id,
        $cliente_id = null,
        $forma_pagamento_id = null,
        $desconto_porcentagem = null,
        $descricao = null
    ) {
        parent::__construct($criarHistoricoRequest);
        $this->cliente_id = $cliente_id;
        $this->loja_id = $loja_id;
        $this->usuario_id = $usuario_id;
        $this->status_id = $status_id;
        $this->forma_pagamento_id = $forma_pagamento_id;
        $this->empresa_master_cod = $empresa_master_cod;
        $this->caixa_id = $caixa_id;
        $this->desconto_porcentagem = $desconto_porcentagem;
        $this->descricao = $descricao;
    }

    public function getCaixaId()
    {
        return $this->caixa_id;
    }

    public function getEmpresaMasterCod()
    {
        return $this->empresa_master_cod;
    }

    public function getClienteId()
    {
        return $this->cliente_id;
    }

    public function setClienteId($cliente_id)
    {
        $this->cliente_id = $cliente_id;
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

    public function getStatusId()
    {
        return $this->status_id;
    }

    public function setStatusId($status_id)
    {
        $this->status_id = $status_id;
    }

    public function getFormaPagamentoId()
    {
        return $this->forma_pagamento_id;
    }

    public function setFormaPagamentoId($forma_pagamento_id)
    {
        $this->forma_pagamento_id = $forma_pagamento_id;
    }

    public function getDescontoPorcentagem()
    {
        return $this->desconto_porcentagem;
    }

    public function setDescontoPorcentagem($desconto_porcentagem)
    {
        $this->desconto_porcentagem = $desconto_porcentagem;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }
}
