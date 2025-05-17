<?php

namespace App\Application;

use App\UseCases\PermissaoUsuario\AdicionarPermissao;
use App\UseCases\PermissaoUsuario\BuscarPermissoes;
use App\UseCases\PermissaoUsuario\BuscarPermissoesPorTipoUsuarioId;
use App\UseCases\PermissaoUsuario\RemoverPermissao;
use App\UseCases\PermissaoUsuario\Requests\AdicionarPermissaoRequest;
use App\UseCases\PermissaoUsuario\Requests\BuscarPermissoesPorTipoUsuarioIdRequest;
use App\UseCases\PermissaoUsuario\Requests\RemoverPermissaoRequest;

class PermissaoUsuarioApplication
{
    public static function buscarPermissoesPorTipoUsuarioId(BuscarPermissoesPorTipoUsuarioIdRequest $request){
        $interact = new BuscarPermissoesPorTipoUsuarioId($request);
        return $interact->handle();
    }

    public static function buscarPermissoes(BuscarPermissoesPorTipoUsuarioIdRequest $request){
        $interact = new BuscarPermissoes($request);
        return $interact->handle();
    }

    public static function adicionarPermissao(AdicionarPermissaoRequest $request){
        $interact = new AdicionarPermissao($request);
        return $interact->handle();
    }

    public static function removerPermissao(RemoverPermissaoRequest $request){
        $interact = new RemoverPermissao($request);
        return $interact->handle();
    }
}
