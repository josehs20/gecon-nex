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
        'valor_total',
        'valor_dinheiro',
        'valor_movimentado',
        'total_credito_loja',
        'caixa_recurso_id',
        'descricao',
    ];

    /**
     * Relacionamento com a tabela caixas.
     */
    public function caixa()
    {
        return $this->belongsTo(Caixa::class);
    }

    /**
     * Relacionamento com a tabela acoes.
     */
    public function acao()
    {
        return $this->belongsTo(Acoes::class);
    }

    /**
     * Relacionamento com a tabela usuarios.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    /**
     * Relacionamento opcional com a tabela caixa_recursos.
     */
    public function recurso()
    {
        return $this->belongsTo(Recurso::class, 'caixa_recurso_id');
    }

    public function venda()
    {
        return $this->hasOne(Venda::class, 'caixa_evidencia_id');
    }

    public function devolucao()
    {
        return $this->hasOne(Devolucao::class, 'caixa_evidencia_id');
    }

    public function sangria()
    {
        return $this->hasOne(Sangria::class, 'caixa_evidencia_id');
    }

    public function suprimento()
    {
        return $this->hasOne(Suprimento::class, 'caixa_evidencia_id');
    }

    public function fichas_cliente()
    {
        return $this->hasMany(FichaCliente::class, 'caixa_evidencia_id');
    }

    public function evidenciaAnterior()
    {
        return self::where('caixa_id', $this->caixa_id)
            ->where('id', '<', $this->id)
            ->orderBy('id', 'desc')
            ->first();
    }
}
