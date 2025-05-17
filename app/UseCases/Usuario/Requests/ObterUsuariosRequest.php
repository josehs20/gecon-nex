<?php

namespace App\UseCases\Usuario\Requests;

class ObterUsuariosRequest
{

    private ?int $loja_id;
    private bool $is_admin;

    public function __construct(
        ?int $loja_id,
        bool $is_admin,
    ) {
        $this->setLojaId($loja_id);        
        $this->setAdmin($is_admin);        
    }

    public function getLojaId(): ?int { return $this->loja_id; }
    public function setLojaId(?int $loja_id): void { $this->loja_id = $loja_id; }

    public function isAdmin(): bool { return $this->is_admin; }
    public function setAdmin(bool $is_admin): void { $this->is_admin = $is_admin; }

}
