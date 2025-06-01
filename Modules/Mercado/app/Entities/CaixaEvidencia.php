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
    public function caixaRecurso()
    {
        return $this->belongsTo(CaixaRecurso::class);
    }

    public function evidenciaAnterior()
    {
        return self::where('caixa_id', $this->caixa_id)
            ->where('id', '<', $this->id)
            ->orderBy('id', 'desc')
            ->first();
    }
}
