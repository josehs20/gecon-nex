<?php

namespace Modules\Mercado\Entities;

class EstoqueRastro extends ModelBase
{
    protected $connection = 'mercado'; // Define a conexão para o banco 'mercado'

    // Define a tabela associada ao modelo
    protected $table = 'estoque_rastro';

    // Define os campos que podem ser preenchidos em massa (mass assignable)
    protected $fillable = [
        'nf_master_cod',
        'estoque_id',
        'produto_id',
        'loja_id',
        'dFab',
        'dVal',
        'nLote',
        'qLote',
        'cAgreg',
    ];

    // Relacionamentos
    public function estoque()
    {
        return $this->belongsTo(Estoque::class, 'estoque_id');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class, 'loja_id');
    }

    // Atributos de data que não precisam ser convertidos para Carbon (se necessário)
    protected $dates = [
        'dFab',
        'dVal',
        'created_at',
        'updated_at',
    ];
}
