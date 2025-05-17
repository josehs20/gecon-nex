<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Modules\Mercado\Repository\Caixa\CaixaRepository;

class GetVendasVoltar
{
    private string $busca;

    public function __construct($busca)
    {
        $this->busca = $busca;
    }

    public function handle()
    {
        return $this->getVendasVoltar();
    }

    private function getVendasVoltar()
    {
        return CaixaRepository::getVendasVoltar($this->busca);
    }
}
