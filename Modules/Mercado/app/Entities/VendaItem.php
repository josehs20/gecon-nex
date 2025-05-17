<?php

namespace Modules\Mercado\Entities;
class VendaItem extends ModelBase
{
    protected $table = 'venda_itens';
    protected $connection = 'mercado';
    protected $fillable = [
        'venda_id',
        'caixa_id',
        'caixa_evidencia_id',
        'estoque_id',
        'loja_id',
        'produto_id',
        'quantidade',
        'preco',
        'total'
    ];

    public function estoque()
    {
        return $this->belongsTo(Estoque::class, 'estoque_id');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    public function venda()
    {
        return $this->belongsTo(Venda::class, 'venda_id');
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class, 'loja_id');
    }

    public function devolucao_item()
    {
        return $this->hasMany(DevolucaoItem::class, 'venda_item_id');
    }
}
