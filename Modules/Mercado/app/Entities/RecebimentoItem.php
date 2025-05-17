<?php

namespace Modules\Mercado\Entities;

class RecebimentoItem extends ModelBase
{

    protected $connection = 'mercado'; // Define a conexão com o banco de dados 'mercado'
    protected $table = 'recebimento_itens'; // Define a tabela associada ao modelo

    protected $fillable = [
        'recebimento_id',
        'loja_id',
        'produto_id',
        'estoque_id',
        'pedido_item_id',
        'status_id',
        'quantidade_recebida',
        'quantidade_pedida',
        'preco_unitario',
        'total',
        'lote',
        'validade',
    ];

    /**
     * Relacionamento com o modelo Recebimento.
     */
    public function recebimento()
    {
        return $this->belongsTo(Recebimento::class, 'recebimento_id');
    }

    /**
     * Relacionamento com o modelo Loja.
     */
    public function loja()
    {
        return $this->belongsTo(Loja::class, 'loja_id');
    }

    /**
     * Relacionamento com o modelo Produto.
     */
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    /**
     * Relacionamento com o modelo Estoque.
     */
    public function estoque()
    {
        return $this->belongsTo(Estoque::class, 'estoque_id');
    }

    /**
     * Relacionamento com o modelo PedidoItem.
     */
    public function pedidoItem()
    {
        return $this->belongsTo(PedidoItem::class, 'pedido_item_id');
    }

    /**
     * Relacionamento com o modelo Status.
     */
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}