<?php

namespace Modules\Mercado\Entities;

use Illuminate\Database\Eloquent\Model;
class Devolucao extends ModelBase
{
    protected $table = 'devolucoes';
    protected $connection = 'mercado';
    protected $fillable = [
        'venda_id',
        'caixa_id',
        'caixa_evidencia_id',
        'loja_id',
        'usuario_id',
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

    public function venda_pagamentos_devolucao()
    {
        return $this->hasMany(VendaPagamentoDevolucao::class, 'devolucao_id');
    }
}
