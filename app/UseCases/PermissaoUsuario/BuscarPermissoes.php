<?php

namespace App\UseCases\PermissaoUsuario;

use App\Repository\PermissaoUsuario\PermissaoUsuarioRepository;
use App\UseCases\PermissaoUsuario\Requests\BuscarPermissoesPorTipoUsuarioIdRequest;

/**
 * Classe responsável por obter todas as permissoes que o usuário NÃO tem
 */

class BuscarPermissoes extends Permissao
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
        $processos = $this->obterPermissoes($this->request->getTipoUsuarioId(), false);

        return $processos->filter(function($processo){
            return $processo->descricao != null; /** São permissões apenas para o ADMIN, por isso são desconsideradas aqui */
        })->map(function($processo){
                return [
                    $processo->descricao,
                    $this->renderizarBotao($processo->id, $this->request->getTipoUsuarioId(), true)
                ];
        })->values();
    }

}
