<?php

namespace Modules\Mercado\UseCases\Pdv\Venda;

use Exception;
use Modules\Mercado\Application\VendaApplication;
use Modules\Mercado\Repository\VendaItem\VendaItemRepository;
use Modules\Mercado\UseCases\Pdv\Venda\Requests\CriarVendaItemRequest;

class CriarVendaItens
{
    private array $itens;
    //espera um array de Modules\Mercado\UseCases\Pdv\Venda\Requests\CriarVendaItemRequest
    public function __construct(array $arrayCriarVendaItemRequest)
    {
        $this->itens = $arrayCriarVendaItemRequest;
    }

    public function handle()
    {
        $this->validade();
        return $this->criarVendaItens();
    }
    private function validade()
    {
        foreach ($this->itens as $key => $item) {
            if (!$item instanceof CriarVendaItemRequest) {
                throw new Exception("Array cotém item com classe direferente de Modules\Mercado\UseCases\Pdv\Venda\Requests\CriarVendaItemRequest", 1);
            }
        }
    }
    private function criarVendaItens()
    {
        $arrayDeItens = [];

        foreach ($this->itens as $item) {
            $arrayDeItens[] = VendaApplication::criaVendaItem($item);
        }
        // VendaItemRepository::createItens($arrayDeItens);
        return $arrayDeItens;
    }
}
