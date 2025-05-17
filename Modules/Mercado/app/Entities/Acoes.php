<?php

namespace Modules\Mercado\Entities;
class Acoes extends ModelBase
{
    protected $table = 'acoes';
    protected $connection = 'mercado';

    protected $fillable = [
        'id',
        'descricao',
        'rota',
    ];
    
}
