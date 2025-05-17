<?php

namespace Modules\Mercado\Entities;

class EstoqueNF extends ModelBase
{
    protected $connection = 'mercado'; // Define a conexão para o banco 'mercado'

    // Define a tabela associada ao modelo
    protected $table = 'estoque_nf';

    // Define os campos que podem ser preenchidos em massa (mass assignable)
    protected $fillable = [
        'nf_master_cod',
        'estoque_id',
        'produto_id',
        'loja_id',
        'cProd',
        'xProd',
        'cEAN',
        'cEANTrib',
        'NCM',
        'CEST',
        'CFOP',
        'vProd',
        'vUnCom',
        'vUnTrib',
        'qCom',
        'qTrib',
        'uCom',
        'uTrib',
        'indTot',
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
        'created_at',
        'updated_at',
    ];
}
