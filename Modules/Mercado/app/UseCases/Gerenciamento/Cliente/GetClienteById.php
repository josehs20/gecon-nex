<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Cliente;

use Modules\Mercado\Repository\Cliente\ClienteRepository;

class GetClienteById
{
    private int $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function handle()
    {
        $cliente = $this->getClienteById();
        return $cliente;
    }

    public function getClienteById()
    {
        return ClienteRepository::getClienteById($this->id);
    }
}
