<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Mercado\Entities\Loja as EntitiesLoja;

class Loja extends Model
{
    protected $table = 'lojas';

    protected $fillable = [
        'nome',
        'empresa_id',
        'ativo',
        'matriz',
        'cnpj',
        'email',
        'modulo_id',
        'status_id',
        'telefone',
        'email',
    ];

    /**
     * Relacionamento com a tabela de status.
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * Relacionamento com a tabela de módulos.
     */
    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function lojaMercado()
    {
        return $this->hasOne(EntitiesLoja::class, 'loja_master_cod', 'id');
    }

    public function nfeio()
    {
        return $this->hasOne(NFEIOLoja::class, 'loja_id', 'id');
    }

    public function certificado()
    {
        return $this->hasOne(Certificado::class, 'loja_id', 'id')->where('ativo', true);
    }

    public function inscricoes_estaduais()
    {
        return $this->hasMany(InscricaoEstadual::class, 'loja_id', 'id');
    }

    public function lojaModulo()
    {
        switch ($this->modulo_id) {
            case config('config.modulos.mercado'):
                return $this->lojaMercado();
                break;

            default:
                # code...
                break;
        }
    }
}
