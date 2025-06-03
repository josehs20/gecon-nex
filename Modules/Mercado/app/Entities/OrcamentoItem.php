<?php

namespace Modules\Mercado\Entities;

class OrcamentoItem extends ModelBase
{
    protected $connection = 'mercado';

    protected $table = 'orcamento_itens';

    protected $fillable = [
        'orcamento_id',
        'estoque_id',
        'loja_id',
        'produto_id',
        'caixa_diario_id',
        'quantidade',
        'preco',
        'total',
    ];

    // RELACIONAMENTOS

    public function orcamento()
    {
        return $this->belongsTo(Orcamento::class);
    }

    public function estoque()
    {
        return $this->belongsTo(\Modules\Mercado\Entities\Estoque::class);
    }

    public function loja()
    {
        return $this->belongsTo(\Modules\Mercado\Entities\Loja::class);
    }

    public function produto()
    {
        return $this->belongsTo(\Modules\Mercado\Entities\Produto::class);
    }

    public function caixaDiario()
    {
        return $this->belongsTo(\Modules\Mercado\Entities\CaixaDiario::class);
    }
}
