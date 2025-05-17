<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NFEIOLoja extends Model
{
    protected $table = 'nfeio_lojas';
    protected $connection = 'gecon';

    protected $fillable = [
        'empresa_id',
        'loja_id',
        'nfeio_id',
        'account_id',
        'name',
        'trade_name',
        'federal_tax_number',
        'tax_regime',
        'status',
        'address'
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class);
    }

    public function inscricao_estadual()
    {
        return $this->hasOne(InscricaoEstadual::class, 'loja_id', 'id');
    }
}
