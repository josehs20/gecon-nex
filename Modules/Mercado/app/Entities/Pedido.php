<?php

namespace Modules\Mercado\Entities;

class Pedido extends ModelBase
{
    // Define a conexão com o banco de dados
    protected $connection = 'mercado';

    // Define o nome da tabela
    protected $table = 'pedidos';

    // Define os campos que podem ser preenchidos
    protected $fillable = [
        'loja_id',
        'status_id',
        'usuario_id',
        'data_limite',
        'observacao',
    ];

    public function recebimento()
    {
        return $this->hasOne(Recebimento::class, 'pedido_id');
    }

    // Relacionamento com a tabela lojas
    public function loja()
    {
        return $this->belongsTo(Loja::class, 'loja_id');
    }

    // Relacionamento com a tabela status
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    // Relacionamento com a tabela usuarios
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function pedido_itens()
    {
        return $this->hasMany(PedidoItem::class, 'pedido_id');
    }
}
