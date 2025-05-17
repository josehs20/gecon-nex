<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Modules\Mercado\Repository\Caixa\CaixaRepository;

class GetClientes
{
    private string $busca;

    public function __construct($busca)
    {
        $this->busca = $busca;
    }

    public function handle()
    {
        return $this->getClientesVendaCaixa();
    }

    private function getClientesVendaCaixa()
    {
        return CaixaRepository::getClientesVendaCaixa($this->busca);
    }
}
