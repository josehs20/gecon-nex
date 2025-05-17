<?php

namespace App\UseCases\PermissaoUsuario;

use App\Repository\PermissaoUsuario\PermissaoUsuarioRepository;
use App\UseCases\PermissaoUsuario\Requests\AdicionarPermissaoRequest;

class AdicionarPermissao extends Permissao
{
    private AdicionarPermissaoRequest $request;

    public function __construct(AdicionarPermissaoRequest $request)
    {
       $this->request = $request;
    }

    public function handle()
    {
        $permissao_existe = $this->permissao_existe($this->request->getProcessoId(),$this->request->getTipoUsuarioId());
        if($permissao_existe){
            return throw new \Exception("O usuário já tem essa permissão!", 400);
        }
        return $this->adicionar();
    }

    private function adicionar(){
        return PermissaoUsuarioRepository::adicionar(
            $this->request->getProcessoId(),
            $this->request->getTipoUsuarioId()
        );
    }

}
