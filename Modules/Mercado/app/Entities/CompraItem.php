<?php

namespace Modules\Mercado\Entities;

class CompraItem extends ModelBase
{
    protected $table = 'compra_itens';
    protected $connection = 'mercado';
    protected $fillable = [
        'compra_id',
        'loja_id',
        'cot_for_item_id',
    ];

    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class);
    }

    public function cotacaoFornecedorItem()
    {
        return $this->belongsTo(CotacaoFornecedorItem::class, 'cot_for_item_id');
    }
}
