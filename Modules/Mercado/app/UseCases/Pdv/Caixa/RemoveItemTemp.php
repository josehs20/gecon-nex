<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Exception;
use Modules\Mercado\Repository\PDV\CaixaPDVRepository;


class RemoveItemTemp
{
    private int $id;
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function handle()
    {
        $item = $this->validade();
        $this->remove();
    
        return CaixaPDVRepository::getItensCaixaTemp($item->caixa_id);
    }

    private function validade()
    {
        $item = CaixaPDVRepository::getItemCaixaTemp($this->id);
        if (!$item) {
            throw new Exception("Item não encontrado ou já removido.", 1);
        }

        return $item;
    }

    private function remove()
    {
        return CaixaPDVRepository::removeCaixaItemTemp($this->id);
    }
}
