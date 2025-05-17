<?php

namespace Modules\Mercado\Entities;

class TipoImposto extends ModelBase
{

    protected $table = 'tipo_imposto'; // Nome da tabela no banco

    protected $fillable = [
        'nome',
        'numero',
        'descricao',
    ];

    /**
     * Define a conexão com o banco de dados correto.
     */
    protected $connection = 'mercado';

    /**
     * Relacionamento com outras tabelas, caso necessário.
     */
    // public function estoqueImpostos()
    // {
    //     return $this->hasMany(EstoqueImposto::class);
    // }
}
