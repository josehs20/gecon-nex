<?php

namespace App\Models;

use App\Models\Loja as ModelsLoja;
use Illuminate\Database\Eloquent\Model;
use Modules\Mercado\Entities\Loja;

class Empresa extends Model
{
    protected $table = 'empresas';
    protected $connection = 'gecon';

    protected $fillable = ['razao_social', 'nome_fantasia', 'cnpj', 'ativo', 'status_id'];

    public function mercadoLojas()
    {
        return $this->hasMany(Loja::class, 'empresa_master_cod', 'id');
    }

    public function lojas()
    {
        return $this->hasMany(ModelsLoja::class, 'empresa_id');
    }

    public function matriz()
    {
        return $this->hasOne(ModelsLoja::class, 'empresa_id', 'id')->where('matriz', true);
    }

    public function getUsuarios()
    {
        $usuarios = $this->mercadoLojas->load('usuarios')->flatMap(function ($loja) {
            return $loja->usuarios;
        });

        return $usuarios->unique('id');
    }
}
