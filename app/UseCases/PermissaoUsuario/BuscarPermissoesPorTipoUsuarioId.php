<?php

namespace App\UseCases\PermissaoUsuario;

use App\UseCases\PermissaoUsuario\Requests\BuscarPermissoesPorTipoUsuarioIdRequest;

/**
 * Classe responsável por obter as permissoes que o usuário já tem
 */

class BuscarPermissoesPorTipoUsuarioId extends Permissao
{
    private BuscarPermissoesPorTipoUsuarioIdRequest $request;

    public function __construct(BuscarPermissoesPorTipoUsuarioIdRequest $request)
    {
       $this->request = $request;
    }

    public function handle()
    {
        return response()->json(['data' => $this->preencherDados()]);
    }

    private function preencherDados(){
        $processos = $this->obterPermissoes($this->request->getTipoUsuarioId(), true);
        return $processos->map(function($processo){
            return [
                $processo->descricao,
                $this->renderizarBotao($processo->id, $this->request->getTipoUsuarioId(), false)
            ];
        });
    }

}
