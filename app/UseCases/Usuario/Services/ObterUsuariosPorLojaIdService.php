<?php

namespace App\UseCases\Usuario\Services;

use App\Repository\Usuario\UsuarioRepository;
use App\UseCases\Usuario\Interfaces\IObterUsuarios;
use Illuminate\Support\Collection;

class ObterUsuariosPorLojaIdService implements IObterUsuarios{
    public function __construct(private bool $is_admin, private ?int $loja_id = null) {}

    public function obterUsuarios(): Collection {
        return UsuarioRepository::obterUsuariosPorLojaId($this->loja_id);
    }
}