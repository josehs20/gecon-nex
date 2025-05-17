<?php

namespace Modules\Mercado\Entities;

use App\Models\User;

class Usuario extends ModelBase
{
    protected $table = 'usuarios';
    protected $connection = 'mercado';
    protected $fillable = [
        'usuario_master_cod',
        'loja_id',
        'endereco_id',
        'status_id',
        'data_nascimento',
        'documento',
        'telefone',
        'celular',
        'ativo',
        'data_admissao',
        'salario',
        'tipo_contrato',
        'data_demissao',
        'comissao'
    ];

    public function loja()
    {
        return $this->belongsTo(Loja::class, 'loja_id');
    }

    public function lojas()
    {
        return $this->belongsToMany(Loja::class, 'lojas_usuario', 'usuario_id', 'loja_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function master()
    {
        return $this->belongsTo(User::class, 'usuario_master_cod');
    }

    public function caixa()
    {
        return $this->hasOne(Caixa::class);
    }

    public function movimentacao_estoque()
    {
        return $this->hasMany(MovimentacaoEstoque::class);
    }

    public function enderecos(){
        return $this->belongsTo(Endereco::class, 'endereco_id');
    }

    public function caixa_permissoes(){
        return $this->hasMany(CaixaPermissao::class, 'usuario_id');
    }
}
