<?php

namespace Modules\Mercado\Entities;

class Recebimento extends ModelBase
{
    protected $table = 'recebimentos';
    protected $connection = 'mercado';
    // Definir os campos preenchíveis
    protected $fillable = [
        'pedido_id',
        'usuario_id',
        'loja_id',
        'status_id',
        'data_recebimento',
        'observacoes',
        'arquivo_id'

    ];

    // Definir os campos que são convertidos para data
    protected $dates = ['data_recebimento', 'created_at', 'updated_at', 'deleted_at'];

    // Relações

    public function arquivo()
    {
        return $this->belongsTo(Arquivo::class, 'arquio_id');
    }

    // Pedido
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    // Usuário
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // Loja
    public function loja()
    {
        return $this->belongsTo(Loja::class, 'loja_id');
    }

    // Status
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    // Status
    public function recebimento_itens()
    {
        return $this->hasMany(RecebimentoItem::class, 'recebimento_id');
    }
}
