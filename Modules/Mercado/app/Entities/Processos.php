<?php

namespace Modules\Mercado\Entities;
class Processos extends ModelBase
{
    protected $table = 'processos';
    protected $connection = 'mercado';

    protected $fillable = [
        'id',
        'nome',
        'descricao',
        'tipo',
        'rota',
        'posicao_menu'
    ];

    public function processo_usuarios()
    {
        return $this->hasMany(ProcessoUsuario::class, 'processo_id');
    }

}
