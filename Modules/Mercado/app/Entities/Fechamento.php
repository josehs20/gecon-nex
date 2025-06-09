<?php

namespace Modules\Mercado\Entities;

class Fechamento extends ModelBase
{
    protected $connection = 'mercado'; // Conexão especificada na sua migração
    protected $table = 'fechamentos'; // Nome da sua tabela

    protected $fillable = [
        'caixa_id',
        'caixa_evidencia_id',
        'usuario_id',
        'valor_total',
        'valor_dinheiro',
        'valor_esperado_dinheiro',
        'motivo',
        'caixa_diario_id',
    ];


    // --- Relacionamentos ---

    /**
     * Um fechamento pertence a um Caixa.
     */
    public function caixa()
    {
        return $this->belongsTo(Caixa::class);
    }

    /**
     * Um fechamento pode ter uma evidência de caixa.
     */
    public function caixaEvidencia()
    {
        return $this->belongsTo(CaixaEvidencia::class);
    }

    /**
     * Um fechamento pertence a um Usuário.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    /**
     * Um fechamento pode estar associado a um CaixaDiario.
     */
    public function caixaDiario()
    {
        return $this->belongsTo(CaixaDiario::class);
    }
}
