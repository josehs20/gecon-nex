<?php

namespace Modules\Mercado\Entities;

class Orcamento extends ModelBase
{
    protected $connection = 'mercado';
    protected $table = 'orcamentos';

    protected $fillable = [
        'cliente_id',
        'loja_id',
        'empresa_master_cod',
        'usuario_id',
        'status_id',
        'forma_pagamento_id',
        'caixa_diario_id',
        'sub_total',
        'total',
        'desconto_porcentagem',
        'desconto_dinheiro',
        'descricao'
    ];

    /**
     * Relações
     */

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function formaPagamento()
    {
        return $this->belongsTo(FormaPagamento::class);
    }

    public function caixaDiario()
    {
        return $this->belongsTo(CaixaDiario::class);
    }
}
