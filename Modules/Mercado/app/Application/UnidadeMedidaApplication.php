<?php

namespace Modules\Mercado\Application;

use Modules\Mercado\UseCases\Gerenciamento\UnidadeMedida\CriarUnidadeMedida;
use Modules\Mercado\UseCases\Gerenciamento\UnidadeMedida\EditarUnidadeMedida;
use Modules\Mercado\UseCases\Gerenciamento\UnidadeMedida\Requests\CriarUnidadeMedidaRequest;
use Modules\Mercado\UseCases\Gerenciamento\UnidadeMedida\Requests\EditarUnidadeMedidaRequest;

class UnidadeMedidaApplication
{
    public static function criarUnidadeMedida(CriarUnidadeMedidaRequest $request)
    {
        $interact = new CriarUnidadeMedida($request);
        return $interact->handle();
    }

    public static function editarUnidadeMedida(EditarUnidadeMedidaRequest $request)
    {
        $interact = new EditarUnidadeMedida($request);
        return $interact->handle();
    }
}
