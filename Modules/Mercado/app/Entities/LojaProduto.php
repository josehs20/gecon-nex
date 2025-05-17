<?php

namespace Modules\Mercado\Entities;
class LojaProduto extends ModelBase
{
    protected $table = 'lojas_produto';
    protected $connection = 'mercado';
    protected $fillable = [
        'produto_id',
        'loja_id',

    ];

    public function lojas()
    {
        return $this->belongsToMany(Loja::class, 'lojas_produto', 'produto_id', 'loja_id');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id', 'id');
    }

    public function estoque()
    {
        return $this->hasOne(Estoque::class, 'loja_produto_id');
    }
}
