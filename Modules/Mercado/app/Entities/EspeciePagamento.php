<?php

namespace Modules\Mercado\Entities;
class EspeciePagamento extends ModelBase
{
    protected $table = 'especie_pagamento';
    protected $connection = 'mercado';

    protected $fillable = [
        'nome',
        'afeta_troco',
        'credito_loja',
        'contem_parcela'
    ];
}
