<?php

namespace Modules\Mercado\Entities;

class PedidoItem extends ModelBase
{

    // Conexão personalizada com o banco de dados
    protected $connection = 'mercado';

    // Nome da tabela
    protected $table = 'pedido_itens';

    // Campos preenchíveis
    protected $fillable = [
        'pedido_id',
        'produto_id',
        'estoque_id',
        'status_id',
        'loja_id',
        'quantidade_pedida',
        'observacao'
    ];

    // Relacionamentos

    /**
     * Relacionamento com o pedido.
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    /**
     * Relacionamento com o produto.
     */
    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    /**
     * Relacionamento com o estoque.
     */
    public function estoque()
    {
        return $this->belongsTo(Estoque::class);
    }

    /**
     * Relacionamento com a loja.
     */
    public function loja()
    {
        return $this->belongsTo(Loja::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function recebimento_item()
    {
        return $this->hasOne(RecebimentoItem::class, 'pedido_item_id');
    }

    public function cotacao_fornecedor_item(){
        return $this->hasOne(CotacaoFornecedorItem::class, 'pedido_item_id', 'id');
    }
}
