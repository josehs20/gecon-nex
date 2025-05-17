<?php

namespace Modules\Mercado\Entities;

class CaixaEvidencia extends ModelBase
{
    protected $table = 'caixa_evidencias';
    protected $connection = 'mercado';
    protected $fillable = [
        'caixa_id',
        'acao_id',
        'usuario_id',
        'ip_address',
        'sistema_operacional',
        'localizacao',
        'ativo',
        'token',
        'valor_abertura',
        'valor_fechamento',
        'valor_sangria',
        'data_abertura',
        'data_fechamento',
        'descricao'
    ];

    public function caixa()
    {
        return $this->belongsTo(Caixa::class, 'caixa_id', 'id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id');
    }

    public function acao()
    {
        return $this->belongsTo(Acoes::class, 'acao_id', 'id');
    }

    public function evidencias()
    {
        return $this->hasMany(CaixaEvidencia::class, 'caixa_id', 'caixa_id');
    }

    public function devolucoes()
    {
        return $this->hasMany(Devolucao::class, 'caixa_evidencia_id', 'id');
    }

    public function vendas()
    {
        return $this->hasMany(Venda::class, 'caixa_evidencia_id', 'id');
    }

    public function pagamentos()
    {
        return $this->hasMany(Pagamento::class, 'caixa_evidencia_id', 'id');
    }
}
