<?php

namespace Modules\Mercado\Entities;

class Pagamento extends ModelBase
{
    protected $table = 'pagamentos';
    protected $connection = 'mercado';
    protected $fillable = [
        'loja_id',
        'caixa_id',
        'caixa_evidencia_id',
        'venda_id',
        'venda_pagamento_id',
        'forma_pagamento_id',
        'especie_pagamento_id',
        'parcelas',
        'data_pagamento',
        'valor',
    ];

    public function venda()
    {
        return $this->belongsTo(Venda::class, 'venda_id');
    }

    public function venda_pagamento()
    {
        return $this->belongsTo(VendaPagamento::class, 'venda_pagamento_id');
    }

    public function forma()
    {
        return $this->belongsTo(FormaPagamento::class, 'forma_pagamento_id');
    }

    public function especie()
    {
        return $this->belongsTo(EspeciePagamento::class, 'especie_pagamento_id');
    }
}
