<?php

namespace Modules\Mercado\Entities;

class Caixa extends ModelBase
{
    protected $table = 'caixa_diario';
    protected $connection = 'mercado';
    protected $fillable = [
        'caixa_id',
        'caixa_evidencia_id',
        'usuario_id',
        'loja_id',
        'status_id',
        'data_abertura',
        'data_fechamento',
    ];

    protected $casts = [
        'data_abertura' => 'datetime',
        'data_fechamento' => 'datetime',
    ];

    public function caixa()
    {
        return $this->belongsTo(Caixa::class);
    }

    public function evidencia()
    {
        return $this->belongsTo(CaixaEvidencia::class, 'caixa_evidencia_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
