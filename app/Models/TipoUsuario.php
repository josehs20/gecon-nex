<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Mercado\Entities\ProcessoUsuario;

class TipoUsuario extends Model
{
    protected $table = 'tipo_usuarios';
    protected $connection = 'gecon';

    protected $fillable = ['descricao', 'funcao', 'perfil'];

    public function users()
    {
        return $this->hasMany(User::class, 'tipo_usuario_id');
    }

    public function processos(){
        return $this->hasMany(ProcessoUsuario::class, 'tipo_usuario_id', 'id');
    }
}
