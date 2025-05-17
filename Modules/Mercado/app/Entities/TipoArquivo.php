<?php

namespace Modules\Mercado\Entities;

class TipoArquivo extends ModelBase
{
    protected $table = 'tipo_arquivo';
    protected $connection = 'mercado';
    protected $fillable = ['descricao'];

}
