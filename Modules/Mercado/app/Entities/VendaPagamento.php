<?php

namespace Modules\Mercado\Entities;

class VendaPagamento extends ModelBase
{
    protected $table = 'venda_pagamentos';
    protected $connection = 'mercado';
    protected $fillable = [
        'venda_id',
        'forma_pagamento_id',
        'especie_pagamento_id',
        'loja_id',
        'parcela',
        'valor',
        'valor_pago',
        'troco',
        'status_id'
    ];

    public function venda()
    {
        return $this->belongsTo(Venda::class, 'venda_id');
    }

    public function forma()
    {
        return $this->belongsTo(FormaPagamento::class, 'forma_pagamento_id');
    }

    public function especie()
    {
        return $this->belongsTo(EspeciePagamento::class, 'especie_pagamento_id');
    }

    public function venda_pagamento_devolucao()
    {
        return $this->hasMany(VendaPagamentoDevolucao::class, 'venda_pagamento_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function getValor()
    {
        return $this->valor - $this->venda_pagamento_devolucao->sum('valor');
    }

}