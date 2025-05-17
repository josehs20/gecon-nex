<?php

namespace Modules\Mercado\Entities;
class Historico extends ModelBase
{
    protected $table = 'historicos';
    protected $connection = 'mercado';
    protected $fillable = [
        'processo_id',
        'acao_id',
        'usuario_id',
        'historico_tabela_id',
        'elemento_id',
        'comentario',
        'data_json'
    ];
}
