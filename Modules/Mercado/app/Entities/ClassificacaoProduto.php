<?php

namespace Modules\Mercado\Entities;

class ClassificacaoProduto extends ModelBase
{
    protected $table = 'classificacao_produto';
    protected $connection = 'mercado';
    protected $fillable = ['descricao', 'empresa_master_cod'];
}
