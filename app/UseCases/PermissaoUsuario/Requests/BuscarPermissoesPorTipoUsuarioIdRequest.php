<?php

namespace App\UseCases\PermissaoUsuario\Requests;

use Modules\Mercado\UseCases\ServiceUseCase;

class BuscarPermissoesPorTipoUsuarioIdRequest extends ServiceUseCase
{

    private int $tipo_usuario_id;

    public function __construct(   
        int $tipo_usuario_id
    ) {
        $this->setTipoUsuarioId($tipo_usuario_id);
    }

    public function getTipoUsuarioId(): int { return $this->tipo_usuario_id; }
    public function setTipoUsuarioId(int $tipo_usuario_id): void { $this->tipo_usuario_id = $tipo_usuario_id; }

}
