<?php

namespace Modules\Mercado\Application;

use Modules\Mercado\UseCases\Senha\Requests\ConfirmarComSenhaRequest;
use Modules\Mercado\UseCases\Senha\ConfirmarComSenha;

class SenhaApplication
{
    public static function confirmarComSenha(ConfirmarComSenhaRequest $request){
        $interact = new ConfirmarComSenha($request);
        return $interact->handle();
    }
}
