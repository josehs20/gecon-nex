<?php

namespace Modules\Mercado\Entities;

class VendaPagamento extends ModelBase
{
    protected $connection = 'mercado';
    protected $table = 'venda_pagamentos';
    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'venda_id',
        'forma_pagamento_id',
        'especie_pagamento_id',
        'loja_id',
        'valor',
        'cliente_id',
        'parcelado',
        'quantidade_parcelas',
        'caixa_diario_id',
    ];


    public function venda()
    {
        return $this->belongsTo(Venda::class, 'venda_id');
    }

    public function formaPagamento()
    {
        return $this->belongsTo(FormaPagamento::class, 'forma_pagamento_id');
    }

    public function especiePagamento()
    {
        return $this->belongsTo(EspeciePagamento::class, 'especie_pagamento_id');
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class, 'loja_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function caixaDiario()
    {
        return $this->belongsTo(CaixaDiario::class, 'caixa_diario_id');
    }

    public function vendaParcelas()
    {
        return $this->hasMany(VendaParcela::class, 'venda_pagamento_id');
    }

    public function devolucoes()
    {
        return $this->hasMany(Devolucao::class, 'venda_id', 'venda_id');
    }
}
