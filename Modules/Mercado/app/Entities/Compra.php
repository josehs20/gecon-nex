<?php

namespace Modules\Mercado\Entities;

class Compra extends ModelBase
{

    protected $table = 'compras';
    protected $connection = 'mercado';
    protected $fillable = [
        'loja_id',
        'usuario_id',
        'cotacao_id',
        'cot_fornecedor_id',
        'status_id',
        'especie_pagamento_id',
    ];

    public function loja()
    {
        return $this->belongsTo(Loja::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function cotacao()
    {
        return $this->belongsTo(Cotacao::class);
    }

    public function cot_fornecedor()
    {
        return $this->belongsTo(CotacaoFornecedor::class, 'cot_fornecedor_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function especie_pagamento()
    {
        return $this->belongsTo(EspeciePagamento::class);
    }
}
