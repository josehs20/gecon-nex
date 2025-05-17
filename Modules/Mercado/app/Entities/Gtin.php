<?php

namespace Modules\Mercado\Entities;

class Gtin extends ModelBase
{
    protected $table = 'gtins';
    protected $connection = 'mercado';
    protected $fillable = [
        'gtin',
        'descricao',
        'tipo',
        'quantidade',
        'comprimento',
        'altura',
        'largura',
        'peso_bruto',
        'peso_liquido',
        'ultima_verificacao',
        'ncm',
    ];
}
