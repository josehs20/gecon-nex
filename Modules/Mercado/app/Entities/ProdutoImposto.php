<?php

namespace Modules\Mercado\Entities;

class ProdutoImposto extends ModelBase
{
    protected $table = 'produto_impostos';
    protected $connection = 'mercado';
    protected $fillable = [
        'produto_id',
        'estoque_id',
        'ncm_codigo',
        'descricao',
        'uf',
        'icms',
        'ipi',
        'valor_importado',
        'valor_nacional',
        'vigencia_inicio',
        'vigencia_fim'
    ];
}
