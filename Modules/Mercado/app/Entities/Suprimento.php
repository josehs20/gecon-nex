<?php

namespace Modules\Mercado\Entities;

class Suprimento extends ModelBase
{

    protected $connection = 'mercado'; // conexão do banco de dados
    protected $table = 'suprimentos';

    protected $fillable = [
        'caixa_id',
        'caixa_evidencia_id',
        'caixa_diario_id',
        'usuario_id',
        'valor',
        'motivo',
    ];

    /**
     * Relação com Caixa
     */
    public function caixa()
    {
        return $this->belongsTo(Caixa::class);
    }

    /**
     * Relação com CaixaEvidencia
     */
    public function caixaEvidencia()
    {
        return $this->belongsTo(CaixaEvidencia::class);
    }

    /**
     * Relação com CaixaDiario
     */
    public function caixaDiario()
    {
        return $this->belongsTo(CaixaDiario::class);
    }

    /**
     * Relação com Usuario
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
