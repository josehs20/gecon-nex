<?php

namespace Modules\Mercado\Entities;

class DevolucaoItem extends ModelBase
{
    protected $table = 'devolucao_itens';
    protected $connection = 'mercado';
    protected $fillable = [
        'devolucao_id',
        'loja_id',
        'venda_id',
        'caixa_id',
        'caixa_evidencia_id',
        'venda_item_id',
        'estoque_origem_id',
        'estoque_destino_id',
        'produto_id',
        'data_devolucao',
        'quantidade',
        'preco',
        'total'
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    public function venda_item()
    {
        return $this->belongsTo(VendaItem::class, 'venda_item_id');
    }

    public function estoque_origem()
    {
        return $this->belongsTo(Estoque::class, 'estoque_origem_id');
    }

    public function estoque_destino()
    {
        return $this->belongsTo(Estoque::class, 'estoque_destino_id');
    }
}
