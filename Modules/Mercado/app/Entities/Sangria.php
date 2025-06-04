<?php

namespace Modules\Mercado\Entities;

class Sangria extends ModelBase
{
 protected $connection = 'mercado';

    protected $table = 'sangrias';

    protected $fillable = [
        'caixa_id',
        'caixa_evidencia_id',
        'usuario_id',
        'especie_pagamento_id',
        'valor',
        'motivo',
        'caixa_diario_id'
    ];

    /**
     * Relacionamento com o Caixa.
     */
    public function caixa()
    {
        return $this->belongsTo(Caixa::class);
    }

    /**
     * Relacionamento com o CaixaEvidencia.
     */
    public function caixaEvidencia()
    {
        return $this->belongsTo(CaixaEvidencia::class, 'caixa_evidencia_id');
    }

    /**
     * Relacionamento com o Usuario.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    /**
     * Relacionamento com a EspeciePagamento.
     */
    public function especiePagamento()
    {
        return $this->belongsTo(EspeciePagamento::class);
    }

    /**
     * Relacionamento com o CaixaDiario.
     */
    public function caixaDiario()
    {
        return $this->belongsTo(CaixaDiario::class);
    }
}
