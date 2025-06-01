<?php

namespace Modules\Mercado\Repository\Usuario;

use Modules\Mercado\Entities\Usuario;

class UsuarioRepository
{
    public static function getUsuarioById(int $id)
    {
        return Usuario::with(['master'])->find($id);
    }
}
