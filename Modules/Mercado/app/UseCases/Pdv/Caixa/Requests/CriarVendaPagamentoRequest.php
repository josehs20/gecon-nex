<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa\Requests;

use Modules\Mercado\Entities\Venda;
use Modules\Mercado\UseCases\Pdv\Venda\Requests\CriarVendaRequest;

class CriarVendaPagamentoRequest
{
    private CriarVendaRequest $requestCriarVenda;
    private Venda $venda;
    public function __construct(CriarVendaRequest $requestCriarVenda, Venda $venda)
    {
        $this->requestCriarVenda = $requestCriarVenda;
        $this->venda = $venda;
    }

    public function getCriarVendaRequest() {
        return $this->requestCriarVenda;
    }

    public function getVenda() {
        return $this->venda;
    }
}