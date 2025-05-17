<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InscricaoEstadual extends Model
{
    protected $table = 'inscricoes_estaduais';
    protected $connection = 'gecon';

    protected $fillable = [
        'loja_id',
        'nfeio_loja_id',
        'state_tax_id',
        'account_id',
        'company_id',
        'code',
        'special_tax_regime',
        'type',
        'tax_number',
        'status',
        'serie',
        'number',
        'processing_details',
        'security_credential',
    ];

    public function loja()
    {
        return $this->belongsTo(Loja::class, 'loja_id');
    }

    public function nfeio()
    {
        return $this->belongsTo(NFEIOLoja::class, 'nfeio_loja_id');
    }
}
