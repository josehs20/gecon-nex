<?php

namespace Modules\Mercado\Entities;

use Illuminate\Database\Eloquent\Model;

class Devolucao extends ModelBase
{
    protected $table = 'devolucoes';
    protected $connection = 'mercado';
    protected $fillable = [
        'venda_id',
        'caixa_id',
        'loja_id',
        'usuario_id',
        'motivo',
        'data_devolucao',
        'total_devolvido',
        'forma_pagamento_id',
        'caixa_diario_id',
        'caixa_evidencia_id',
    ];

    /**
     * Relacionamento com a venda.
     */
    public function venda()
    {
        return $this->belongsTo(Venda::class);
    }

    /**
     * Relacionamento com o caixa.
     */
    public function caixa()
    {
        return $this->belongsTo(Caixa::class);
    }

    /**
     * Relacionamento com a loja.
     */
    public function loja()
    {
        return $this->belongsTo(Loja::class);
    }

    /**
     * Relacionamento com o usuário.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    /**
     * Relacionamento com a forma de pagamento.
     */
    public function formaPagamento()
    {
        return $this->belongsTo(FormaPagamento::class);
    }

    /**
     * Relacionamento com o caixa diário.
     */
    public function caixaDiario()
    {
        return $this->belongsTo(CaixaDiario::class);
    }

    /**
     * Relacionamento com a caixa evidência.
     */
    public function caixaEvidencia()
    {
        return $this->belongsTo(CaixaEvidencia::class);
    }
}
