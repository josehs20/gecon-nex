<?php

namespace Modules\Mercado\Entities;

use App\Models\Empresa;
use App\Models\InscricaoEstadual;
use App\Models\Loja as ModelsLoja;
use App\Models\NFEIOLoja;

class Loja extends ModelBase
{
    protected $table = 'lojas';
    protected $connection = 'mercado';
    protected $fillable = ['nome', 'empresa_master_cod', 'loja_master_cod','endereco_id','cnpj', 'status_id'];


    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_master_cod');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function endereco()
    {
        return $this->belongsTo(Endereco::class, 'endereco_id');
    }

    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'loja_id', 'id');
    }

    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'lojas_usuario', 'loja_id', 'usuario_id');
    }

    // public function produtos()
    // {
    //     return $this->belongsToMany(Produto::class, 'lojas_produto', 'loja_id', 'produto_id');
    // }

    public function nfeio()
    {
        return $this->belongsTo(NFEIOLoja::class, 'loja_master_cod', 'loja_id');
    }

    public function inscricoes_estaduais()
    {
        return $this->hasMany(InscricaoEstadual::class, 'loja_id', 'loja_master_cod');
    }

    public function loja()
    {
        return $this->belongsTo(ModelsLoja::class, 'loja_master_cod', 'id');
    }

    public function estoques()
    {
        return $this->hasMany(Estoque::class, 'loja_id');
    }
}
