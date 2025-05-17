<?php

namespace Modules\Mercado\UseCases\Pedido\Cotacao\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class CriarCotForItensRequest extends ServiceUseCase
{
    private $pedido_id;
    private $cot_fornecedor_id;

    public function __construct($pedido_id, $cot_fornecedor_id, CriarHistoricoRequest $criarHistoricoRequest)
    {
        parent::__construct($criarHistoricoRequest);
        $this->pedido_id = $pedido_id;
        $this->cot_fornecedor_id = $cot_fornecedor_id;
    }

    public function getPedidoId()
    {
        return $this->pedido_id;
    }

    public function setPedidoId($pedido_id)
    {
        $this->pedido_id = $pedido_id;
    }

    public function getCotFornecedorId()
    {
        return $this->cot_fornecedor_id;
    }

    public function setCotFornecedorId($cot_fornecedor_id)
    {
        $this->cot_fornecedor_id = $cot_fornecedor_id;
    }
}

