<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Cliente;

use Modules\Mercado\Repository\Cliente\ClienteRepository;

class GetTodosClientesPorAtivo
{
    private bool $ativo;

    public function __construct($ativo)
    {
        $this->ativo = $ativo;
    }

    public function handle()
    {
        $clientes = $this->getTodosClientesPorAtivo();
        return $clientes;
    }

    public function getTodosClientesPorAtivo()
    {
        return ClienteRepository::getTodosClientesPorAtivo($this->ativo);
    }
}
