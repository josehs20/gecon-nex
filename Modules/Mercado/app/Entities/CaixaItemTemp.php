<?php

namespace Modules\Mercado\Entities;

class CaixaItemTemp extends ModelBase
{

    protected $connection = 'mercado';
    protected $table = 'caixa_itens_temp';

    protected $fillable = [
        'estoque_id',
        'produto_id',
        'caixa_id',
        'quantidade',
        'preco',
        'total',
    ];

    /**
     * Relações
     */

    public function estoque()
    {
        return $this->belongsTo(Estoque::class);
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function caixa()
    {
        return $this->belongsTo(Caixa::class);
    }
}
