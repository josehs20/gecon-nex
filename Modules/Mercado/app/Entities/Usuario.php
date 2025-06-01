<?php

namespace Modules\Mercado\Entities;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

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

    public function enderecos()
    {
        return $this->belongsTo(Endereco::class, 'endereco_id');
    }

    public function caixa_permissoes()
    {
        return $this->hasMany(CaixaPermissao::class, 'usuario_id');
    }

    public function caixa_permissoes_loja()
    {
        return $this->hasMany(CaixaPermissao::class, 'usuario_id')->whereHas('caixa', function ($q) {
            $q->where('loja_id', auth()->user()->getUserModulo->loja_id);
        });
    }

    public function verificaSenha($senha)
    {
        if (!Hash::check($senha, $this->master->password)) {
            throw new Exception("Senha incorreta!.", 1);
        }
        return true;
    }
}
