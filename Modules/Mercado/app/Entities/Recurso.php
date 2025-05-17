<?php

namespace Modules\Mercado\Entities;

class Recurso extends ModelBase
{

    protected $connection = 'mercado'; // Define a conexão com o banco de dados 'mercado'
    protected $table = 'recursos'; // Define a tabela associada ao modelo

    protected $fillable = [
        'nome',
        'descricao',
    ];

    // Model Recurso
    public function caixas()
    {
        return $this->belongsToMany(Caixa::class, 'caixa_recursos', 'recurso_id', 'caixa_id')
            // ->withPivot('nome', 'descricao') // Campos adicionais da pivot
            ->withTimestamps();
    }
}
