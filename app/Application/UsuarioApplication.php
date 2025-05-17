<?php

namespace App\Application;

use App\UseCases\Usuario\AtualizarUsuario;
use App\UseCases\Usuario\CriarUsuario;
use App\UseCases\Usuario\ObterUsuarios;
use App\UseCases\Usuario\PreencherListagemUsuariosDatatables;
use App\UseCases\Usuario\Requests\ObterUsuariosRequest;
use App\UseCases\Usuario\Requests\PreencherListagemUsuariosDatatablesRequest;
use App\UseCases\Usuario\Requests\UsuarioRequest;

class UsuarioApplication
{
    public static function criar(UsuarioRequest $request){
        $interact = new CriarUsuario($request);
        return $interact->handle();
    }

    public static function atualizar(UsuarioRequest $request){
        $interact = new AtualizarUsuario($request);
        return $interact->handle();
    }

    public static function obterUsuarios(ObterUsuariosRequest $request){
        $interact = new ObterUsuarios($request);
        return $interact->handle();
    }

    public static function preencherListagemUsuariosDatatables(PreencherListagemUsuariosDatatablesRequest $request){
        $interact = new PreencherListagemUsuariosDatatables($request);
        return $interact->handle();
    }
}
