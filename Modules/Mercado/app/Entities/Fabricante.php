<?php

namespace Modules\Mercado\Entities;
class Fabricante extends ModelBase
{
    protected $table = 'fabricantes';
    protected $connection = 'mercado';
    protected $fillable = [
        'nome',
        'descricao',
        'cnpj', 
        'razao_social', 
        'inscricao_estadual', 
        'endereco_id', 
        'celular', 
        'telefone', 
        'email', 
        'site', 
        'ativo',
        'empresa_master_cod'
    ];

    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }

    public function endereco(){
        return $this->belongsTo(Endereco::class);
    }
}

