<?php

namespace Modules\Mercado\UseCases\Senha\Requests;

use Modules\Mercado\Entities\Usuario;

class ConfirmarComSenhaRequest
{
    private string $senha;
    private Usuario $usuario;

    public function __construct(string $senha, Usuario $usuario)
    {
        $this->senha = $senha;
        $this->usuario = $usuario;
    }

    public function getSenha()
    {
        return $this->senha;
    }

    public function setSenha(string $senha)
    {
        $this->senha = $senha;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario)
    {
        $this->usuario = $usuario;
    }
}
