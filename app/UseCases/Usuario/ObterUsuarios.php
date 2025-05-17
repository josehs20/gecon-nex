<?php

namespace App\UseCases\Usuario;

use App\UseCases\Usuario\Requests\ObterUsuariosRequest;
use App\UseCases\Usuario\Services\ObterUsuariosPorLojaIdService;
use App\UseCases\Usuario\Services\ObterTodosUsuariosService;

class ObterUsuarios
{
    private ObterUsuariosRequest $request;

    public function __construct(ObterUsuariosRequest $request)
    {
       $this->request = $request;
    }

    public function handle()
    {
        return $this->tratar($this->request->isAdmin(), $this->request->getLojaId());
    }

    private function tratar(bool $is_admin, ?int $loja_id = null){
        if($is_admin){
            $todos = new ObterTodosUsuariosService($is_admin);
            return $todos->obterUsuarios();
        }
        $por_loja = new ObterUsuariosPorLojaIdService($is_admin, $loja_id);
        return $por_loja->obterUsuarios();
    }

}
