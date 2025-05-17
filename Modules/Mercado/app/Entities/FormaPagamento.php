<?php

namespace Modules\Mercado\Entities;

class FormaPagamento extends ModelBase
{
    protected $table = 'forma_pagamentos';
    protected $connection = 'mercado';

    protected $fillable = [
        'descricao',
        'ativo',
        'especie_pagamento_id',
        'loja_id',
    ];


    public function loja()
    {
        return $this->belongsTo(Loja::class, 'loja_id')->where('id', auth()->user()->usuarioMercado->loja_id);
    }

    public function especie()
    {
        return $this->belongsTo(EspeciePagamento::class, 'especie_pagamento_id');
    }
}
