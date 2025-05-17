<?php

namespace App\UseCases\PermissaoUsuario\Requests;

use Modules\Mercado\UseCases\ServiceUseCase;

class RemoverPermissaoRequest extends ServiceUseCase
{

    private int $processo_id;
    private int $tipo_usuario_id;

    public function __construct(   
        int $processo_id,
        int $tipo_usuario_id
    ) {
        $this->setProcessoId($processo_id);
        $this->setTipoUsuarioId($tipo_usuario_id);
    }

    public function getProcessoId(): int { return $this->processo_id; }
    public function setProcessoId(int $processo_id): void { $this->processo_id = $processo_id; }

    public function getTipoUsuarioId(): int { return $this->tipo_usuario_id; }
    public function setTipoUsuarioId(int $tipo_usuario_id): void { $this->tipo_usuario_id = $tipo_usuario_id; }

}
