<?php

namespace Modules\Mercado\Entities;

use App\Models\Empresa;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliente extends ModelBase
{
    protected $table = 'clientes';
    protected $connection = 'mercado';
    protected $fillable = [
        'empresa_master_cod',
        'nome',
        'documento',
        'pessoa',
        'ativo',
        'status_id',
        'celular',
        'telefone_fixo',
        'email',
        'data_nascimento',
        'observacao',
        'endereco_id'
    ];

    public function endereco()
    {
        return $this->belongsTo(Endereco::class, 'endereco_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function credito()
    {
        return $this->hasOne(CreditoCliente::class, 'cliente_id','id');
    }

    public function vendas()
    {
        return $this->hasMany(Venda::class, 'cliente_id','id');
    }

    public function getLimiteDisponivel()
    {
        return $this->credito->credito_loja - $this->credito->credito_loja_usado ?? 0;
    }

    //passar estilo como true para retornar o tipo
    public function getStatus($estilo = false)
    {

        $estilos = [
            config('config.status.em_dia') => 'success',
            config('config.status.em_atraso') => 'warning',
            config('config.status.quitado') => 'info',
            config('config.status.bloqueado') => 'danger',

        ];
        if ($estilo === true) {
            return $estilos[$this->status_id];
        }

        switch ($this->status_id) {
            case config('config.status.em_dia'):
                return 'Em dia';
            case config('config.status.em_atraso'):
                return 'Em atraso';
            case config('config.status.quitado'):
                return 'Quitado';
            case config('config.status.bloqueado'):
                return 'Bloqueado';
            case config('config.status.bloqueado'):
                return 'Bloqueado';
            default:
                return 'Não informado';
        }
    }
}
