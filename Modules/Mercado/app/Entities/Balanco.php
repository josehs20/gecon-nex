<?php

namespace Modules\Mercado\Entities;
class Balanco extends ModelBase
{
    protected $table = 'balanco';
    protected $connection = 'mercado';
    protected $fillable = [
        'loja_id',
        'status_id',
        'usuario_id',
        'observacao'
    ];

    public function loja(){
        return $this->belongsTo(Loja::class, 'loja_id');
    }

    public function status(){
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class,  'usuario_id', 'id');
    }

    public function balanco_itens()
    {
        return $this->hasMany(BalancoItem::class, 'balanco_id', 'id');
    }
}
