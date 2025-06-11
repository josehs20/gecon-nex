<?php

namespace Modules\Mercado\Entities;

class FichaCliente extends ModelBase
{
    protected $connection = 'mercado';
    protected $table = 'ficha_cliente';

    protected $fillable = [
        'cliente_id',
        'loja_id',
        'venda_id',
        'valor',
        'caixa_evidencia_id',
        'venda_pagamento_id',
        'venda_parcela_id',
        'caixa_diario_id',
        'forma_pagamento_id',
    ];

    // Relações

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function formaPagamento()
    {
        return $this->belongsTo(FormaPagamento::class, 'forma_pagamento_id');
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class, 'loja_id');
    }

    public function venda()
    {
        return $this->belongsTo(Venda::class, 'venda_id');
    }

    public function vendaPagamento()
    {
        return $this->belongsTo(VendaPagamento::class, 'venda_pagamento_id');
    }

    public function vendaParcela()
    {
        return $this->belongsTo(VendaParcela::class, 'venda_parcela_id');
    }

    public function caixaDiario()
    {
        return $this->belongsTo(CaixaDiario::class, 'caixa_diario_id');
    }
}
