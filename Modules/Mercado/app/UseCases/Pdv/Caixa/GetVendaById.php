<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Modules\Mercado\Repository\Caixa\CaixaRepository;
use Modules\Mercado\Repository\Venda\VendaRepository;

class GetVendaById
{
    private int $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function handle()
    {
        return $this->getVenda();
    }

    private function getVenda()
    {
        return VendaRepository::getVendaById($this->id);
    }
}
