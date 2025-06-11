<?php

namespace Modules\Mercado\Entities;

class Recebimento extends ModelBase
{
    protected $table = 'recebimentos';
    protected $connection = 'mercado';
    // Definir os campos preenchíveis
    protected $fillable = [
        'compra_id',
        'usuario_id',
        'loja_id',
        'status_id',
        'data_recebimento',
        'observacoes',
    ];

    // Definir os campos que são convertidos para data
    protected $dates = ['data_recebimento', 'created_at', 'updated_at', 'deleted_at'];

    // Relações

    // Compra
    public function compra()
    {
        return $this->belongsTo(Compra::class, 'compra_id');
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
