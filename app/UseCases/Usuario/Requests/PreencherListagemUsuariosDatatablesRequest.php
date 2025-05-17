<?php

namespace App\UseCases\Usuario\Requests;

use Illuminate\Support\Collection;

class PreencherListagemUsuariosDatatablesRequest
{

    private Collection $usuarios;
    private bool $is_admin;
    private bool $is_usuario_master;

    public function __construct(
        Collection $usuarios,
        bool $is_admin,
        bool $is_usuario_master,
    ) {
        $this->setUsuarios($usuarios);        
        $this->setAdmin($is_admin);        
        $this->setUsuarioMaster($is_usuario_master);        
    }

    public function getUsuarios(): Collection { return $this->usuarios; }
    public function setUsuarios(Collection $usuarios): void { $this->usuarios = $usuarios; }

    public function isAdmin(): bool { return $this->is_admin; }
    public function setAdmin(bool $is_admin): void { $this->is_admin = $is_admin; }

    public function isUsuarioMaster(): bool { return $this->is_usuario_master; }
    public function setUsuarioMaster(bool $is_usuario_master): void { $this->is_usuario_master = $is_usuario_master; }

}
