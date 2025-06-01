<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaixaMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $usuario = Auth::user();
        if (!$usuario || !$usuario->getUserModulo) {
            throw new Exception("Usuário não esta logado.", 1);
        }

        $caixa = $usuario->getUserModulo->caixa;

        if (!$caixa) {
            throw new Exception("Usuário não está logado em nenhum caixa.", 1);
        }
        //adiciona caixa na request para evitar outras consultas desnecessárias
        $request->attributes->set('caixa_id', $caixa->id);
        $request->attributes->set('loja_id', $caixa->loja_id);

        //validar token do browser com backend

        return $next($request);
    }
}
