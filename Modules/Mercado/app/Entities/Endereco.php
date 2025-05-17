<?php

namespace Modules\Mercado\Entities;
class Endereco extends ModelBase
{
    protected $table = 'enderecos';
    protected $connection = 'mercado';
    protected $fillable = [
        'logradouro',
        'numero',
        'cidade',
        'bairro',
        'uf',
        'cep',
        'complemento',
    ];


    public function loja()
    {
        return $this->belongsTo(Loja::class);
    }

    public function fornecedor(){
        return $this->hasOne(Fornecedor::class);
    }

    public function cliente(){
        return $this->hasOne(Cliente::class);
    }

    public function usuario(){
        return $this->hasOne(Usuario::class);
    }
}
