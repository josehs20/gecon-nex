<?php

namespace Modules\Mercado\Entities;
class UnidadeMedida extends ModelBase
{
    protected $table = 'unidade_medida';
    protected $connection = 'mercado';
    protected $fillable = ['descricao', 'sigla','pode_ser_float', 'empresa_master_cod'];
}
