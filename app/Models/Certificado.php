<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificado extends Model
{
    protected $table = 'certificados';

    protected $fillable = [
        'empresa_id',
        'nfeio_loja_id',
        'ativo',
        'loja_id',
        'caminho',
        'senha',
        'expiracao',
        'status',
    ];
}
