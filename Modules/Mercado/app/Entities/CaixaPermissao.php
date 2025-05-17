<?php

namespace Modules\Mercado\Entities;

class CaixaPermissao extends ModelBase
{
    protected $table = 'caixa_permissoes';
    protected $connection = 'mercado';
    protected $fillable = [
        'caixa_id',
        'usuario_id',
        'superior',
    ];

    protected $casts = [
        'superior' => 'boolean',
    ];

    public function caixa()
    {
        return $this->belongsTo(Caixa::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
