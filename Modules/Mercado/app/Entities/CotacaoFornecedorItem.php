<?php

namespace Modules\Mercado\Entities;

class CotacaoFornecedorItem extends ModelBase
{
    protected $table = 'cotacao_fornecedor_itens';
    protected $connection = 'mercado';
    protected $fillable = [
        'cotacao_fornecedor_id',
        'fornecedor_id',
        'pedido_id',
        'cotacao_id',
        'pedido_item_id',
        'loja_id',
        'estoque_id',
        'produto_id',
        'status_id',
        'quantidade',
        'preco_unitario'
    ];

    public function cotacaoFornecedor()
    {
        return $this->belongsTo(CotacaoFornecedor::class);
    }

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class);
    }

    public function pedidoItem()
    {
        return $this->belongsTo(PedidoItem::class);
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function cotacao()
    {
        return $this->belongsTo(Cotacao::class);
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class);
    }

    public function estoque()
    {
        return $this->belongsTo(Estoque::class);
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function preco_unitario()
    {
        return $this->preco_unitario ? converteCentavosParaFloat($this->preco_unitario) : 0;
    }
}
