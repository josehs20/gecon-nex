<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Modules\Mercado\Repository\Caixa\CaixaRepository;
use Modules\Mercado\Repository\PDV\CaixaPDVRepository;

class GetProdutos
{
    private string $busca;
    private int $loja_id;

    public function __construct(int $loja_id, $busca = '')
    {
        $this->busca = $busca;
        $this->loja_id = $loja_id;
    }

    public function handle()
    {
        return $this->getProdutosVendaCaixa();
    }

    private function getProdutosVendaCaixa()
    {
        return CaixaPDVRepository::getProdutosVendaCaixa($this->loja_id, $this->busca);
    }
}
