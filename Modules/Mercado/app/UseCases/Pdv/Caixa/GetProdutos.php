<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Modules\Mercado\Repository\Caixa\CaixaRepository;

class GetProdutos
{
    private string $busca;

    public function __construct($busca)
    {
        $this->busca = $busca;
    }

    public function handle()
    {
        return $this->getProdutosVendaCaixa();
    }

    private function getProdutosVendaCaixa()
    {
        return CaixaRepository::getProdutosVendaCaixa($this->busca);
    }
}
