<?php

namespace Modules\Mercado\Entities;

class Histolucao extends ModelBase
{
    protected $table = 'hist_devolucoes';
    protected $connection = 'mercado';
    protected $fillable = [
        'devolucao_id',
        'venda_id',
        'caixa_id',
        'loja_id',
        'usuario_id',
        'especie_pagamento_id',
        'motivo',
        'data_devolucao',
        'total_devolvido'
    ];

    public function venda()
    {
        return $this->belongsTo(Venda::class, 'venda_id');
    }

    public function devolucao_itens()
    {
        return $this->hasMany(DevolucaoItem::class, 'devolucao_id');
    }

    public function especie()
    {
        return $this->belongsTo(EspeciePagamento::class, 'especie_pagamento_id');
    }
}
