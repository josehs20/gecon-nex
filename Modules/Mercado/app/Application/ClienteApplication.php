<?php

namespace Modules\Mercado\Application;

use Modules\Mercado\UseCases\Gerenciamento\Cliente\AtualizarCliente;
use Modules\Mercado\UseCases\Gerenciamento\Cliente\CriarCliente;
use Modules\Mercado\UseCases\Gerenciamento\Cliente\GetClienteById;
use Modules\Mercado\UseCases\Gerenciamento\Cliente\GetTodosClientesPorAtivo;
use Modules\Mercado\UseCases\Gerenciamento\Cliente\Requests\ClienteRequest;

class ClienteApplication
{
    public static function criarCliente(ClienteRequest $request){
        $interact = new CriarCliente($request);
        return $interact->handle();
    }

    public static function atualizarCliente(ClienteRequest $request, $id){
        $interact = new AtualizarCliente($request, $id);
        return $interact->handle();
    }

    public static function getTodosClientes(bool $ativo){
        $interact = new GetTodosClientesPorAtivo($ativo);
        return $interact->handle();
    }

    public static function getClienteById(int $id){
        $interact= new GetClienteById($id);
        return $interact->handle();
    }
}
