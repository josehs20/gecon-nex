<?php

namespace App\UseCases\PermissaoUsuario;

use App\Repository\PermissaoUsuario\PermissaoUsuarioRepository;
use App\UseCases\PermissaoUsuario\Requests\RemoverPermissaoRequest;

class RemoverPermissao extends Permissao
{
    private RemoverPermissaoRequest $request;

    public function __construct(RemoverPermissaoRequest $request)
    {
       $this->request = $request;
    }

    public function handle()
    {
        $permissao_existe = $this->permissao_existe($this->request->getProcessoId(),$this->request->getTipoUsuarioId());
        if(!$permissao_existe){
            return throw new \Exception("O usuário não tem essa permissão!", 400);
        }
        return $this->remover();
    }

    private function remover(){
        return PermissaoUsuarioRepository::remover(
            $this->request->getProcessoId(),
            $this->request->getTipoUsuarioId()
        );
    }
}
