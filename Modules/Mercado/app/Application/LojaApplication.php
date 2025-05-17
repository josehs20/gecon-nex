<?php

namespace Modules\Mercado\Application;

use Modules\Mercado\UseCases\Loja\CriarLoja;
use Modules\Mercado\UseCases\Loja\EditarLoja;
use Modules\Mercado\UseCases\Loja\Requests\CriarLojaRequest;

class LojaApplication
{
    public static function criarLoja(CriarLojaRequest $request)
    {
        $interact = new CriarLoja($request);
        return $interact->handle();
    }

    public static function editarLoja(int $id, CriarLojaRequest $request)
    {
        $interact = new EditarLoja($id, $request);
        return $interact->handle();
    }
}
