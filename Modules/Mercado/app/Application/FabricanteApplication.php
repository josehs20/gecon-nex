<?php

namespace Modules\Mercado\Application;

use Modules\Mercado\UseCases\Gerenciamento\Fabricante\AtualizarFabricante;
use Modules\Mercado\UseCases\Gerenciamento\Fabricante\CriarFabricante;
use Modules\Mercado\UseCases\Gerenciamento\Fabricante\Requests\AtualizarFabricanteRequest;
use Modules\Mercado\UseCases\Gerenciamento\Fabricante\Requests\CriarFabricanteRequest;

class FabricanteApplication
{
    public static function atualizarFabricante(AtualizarFabricanteRequest $request){
        $interact = new AtualizarFabricante($request);
        return $interact->handle();
    }

    public static function criarFabricante(CriarFabricanteRequest $request){
        $interact = new CriarFabricante($request);
        return $interact->handle();
    }
}
