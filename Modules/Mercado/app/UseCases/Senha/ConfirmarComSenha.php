<?php

namespace Modules\Mercado\UseCases\Senha;

use Illuminate\Support\Facades\Hash;
use Modules\Mercado\UseCases\Senha\Requests\ConfirmarComSenhaRequest;

class ConfirmarComSenha
{
    private ConfirmarComSenhaRequest $request;

    public function __construct(ConfirmarComSenhaRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        return $this->confirmarSenha();
    }

    private function confirmarSenha(){
        if(Hash::check($this->request->getSenha(), $this->request->getUsuario()->master->password)){
            return true;
        }else {
            return false;
        }
    }
 
}
