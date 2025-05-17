<?php

namespace Modules\Mercado\Entities;

class RecursoCaixa extends ModelBase
{

    protected $connection = 'mercado'; // Define a conexão com o banco de dados 'mercado'
    protected $table = 'caixa_recursos';

    protected $fillable = [
        'nome',
        'caixa_id',
        'recurso_id',
        'descricao',
    ];

    /**
     * Relacionamento com o caixa
     */
    public function caixa()
    {
        return $this->belongsTo(Caixa::class);
    }

    /**
     * Relacionamento com o recurso
     */
    public function recurso()
    {
        return $this->belongsTo(Recurso::class);
    }
}
