<?php

namespace Modules\Mercado\Entities;

class VendaParcela extends ModelBase
{
    protected $connection = 'mercado';
    protected $table = 'venda_parcelas';

    protected $fillable = [
        'venda_id',
        'loja_id',
        'venda_pagamento_id',
        'numero_parcela',
        'valor',
        'valor_pago',
        'status_id',
        'pago',
        'data_vencimento',
        'data_pagamento',
        'forma_pagamento_id',
        'cliente_id',
        'caixa_diario_id',
    ];

    // Relações

    public function venda()
    {
        return $this->belongsTo(Venda::class, 'venda_id');
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class, 'loja_id');
    }

    public function vendaPagamento()
    {
        return $this->belongsTo(VendaPagamento::class, 'venda_pagamento_id');
    }

    public function formaPagamento()
    {
        return $this->belongsTo(FormaPagamento::class, 'forma_pagamento_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function caixaDiario()
    {
        return $this->belongsTo(CaixaDiario::class, 'caixa_diario_id');
    }

    public function fichasCliente()
    {
        return $this->belongsTo(FichaCliente::class, 'venda_pagamento_id');
    }

}
