<?php

namespace Modules\Mercado\Entities;
class Estoque extends ModelBase
{
    protected $table = 'estoques';
    protected $connection = 'mercado';

    protected $fillable = [
        'custo',
        'preco',
        'produto_id',
        'loja_id',
        'quantidade_total',
        'quantidade_disponivel',
        'quantidade_minima',
        'quantidade_maxima',
        'localizacao',
        'ncm_id',
        'produto_imposto_id'
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class, 'loja_id');
    }
}
