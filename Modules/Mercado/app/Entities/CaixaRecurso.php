<?php

namespace Modules\Mercado\Entities;

class CaixaRecurso extends ModelBase
{
    protected $table = 'caixa_recursos';
    protected $connection = 'mercado';

    protected $fillable = [
        'caixa_id',
        'recurso_id',
    ];

    public function caixa()
    {
        return $this->belongsTo(Caixa::class);
    }

    public function recurso()
    {
        return $this->belongsTo(Recurso::class);
    }
}
