<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Modules\Mercado\Repository\Caixa\CaixaRepository;

class GetVendasDevolucao
{
    private ?string $busca;

    public function __construct($busca = '')
    {
        $this->busca = $busca;
    }

    public function handle()
    {
        return $this->getVendasDevolucao();
    }

    private function getVendasDevolucao()
    {
        return CaixaRepository::getVendasDevolucao($this->busca);
    }
}
