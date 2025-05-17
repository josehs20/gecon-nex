<?php

namespace App\UseCases\Usuario\Services;

use App\Repository\Usuario\UsuarioRepository;
use App\UseCases\Usuario\Interfaces\IObterUsuarios;
use Illuminate\Support\Collection;

class ObterTodosUsuariosService implements IObterUsuarios{
    public function __construct(private bool $is_admin) {}

    public function obterUsuarios(): Collection {       
        return UsuarioRepository::obterTodosUsuarios();
    }
}