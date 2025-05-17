<?php

namespace Modules\Mercado\UseCases\Pedido\Compra\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class CriarCompraRequest extends ServiceUseCase
{
private $loja_id;
    private $usuario_id;
    private $cotacao_id;
    private $cot_fornecedor_id;
    private $status_id;
    private $especie_pagamento_id;

    public function __construct(
       int $loja_id,
       int $usuario_id,
       int $cotacao_id,
       int $cot_fornecedor_id,
       int $status_id,
       int $especie_pagamento_id,
       CriarHistoricoRequest $criarHistoricoRequest
    ) {
        parent::__construct($criarHistoricoRequest);
        $this->loja_id = $loja_id;
        $this->usuario_id = $usuario_id;
        $this->cotacao_id = $cotacao_id;
        $this->cot_fornecedor_id = $cot_fornecedor_id;
        $this->status_id = $status_id;
        $this->especie_pagamento_id = $especie_pagamento_id;
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

    public function getCotacaoId()
    {
        return $this->cotacao_id;
    }

    public function setCotacaoId($cotacao_id)
    {
        $this->cotacao_id = $cotacao_id;
    }

    public function getCotFornecedorId()
    {
        return $this->cot_fornecedor_id;
    }

    public function setCotFornecedorId($cot_fornecedor_id)
    {
        $this->cot_fornecedor_id = $cot_fornecedor_id;
    }

    public function getStatusId()
    {
        return $this->status_id;
    }

    public function setStatusId($status_id)
    {
        $this->status_id = $status_id;
    }

    public function getEspeciePagamentoId()
    {
        return $this->especie_pagamento_id;
    }

    public function setEspeciePagamentoId($especie_pagamento_id)
    {
        $this->especie_pagamento_id = $especie_pagamento_id;
    }
}
