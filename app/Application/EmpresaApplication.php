<?php

namespace App\Application;

use App\UseCases\Empresa\CriarEmpresa;
use App\UseCases\Empresa\EditarEmpresa;
use App\UseCases\Empresa\Requests\CriarEmpresaRequest;

class EmpresaApplication
{
    public static function criarEmpresa(CriarEmpresaRequest $request)
    {
        $interact = new CriarEmpresa($request);
        return $interact->handle();
    }

    public static function editarEmpresa(int $id, CriarEmpresaRequest $request)
    {
        $interact = new EditarEmpresa($id, $request);
        return $interact->handle();
    }
}
