<?php

namespace Modules\Mercado\Entities;

class NCM extends ModelBase
{
    protected $table = 'ncms';
    protected $connection = 'mercado';
    protected $fillable = [
        'codigo',
        'descricao',
        'data_inicio',
        'data_fim',
        'tipo_ato_ini',
        'numero_ato_ini',
        'ano_ato_ini'
    ];
}
