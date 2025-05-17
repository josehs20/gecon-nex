<?php

namespace Modules\Mercado\Entities;
class Fornecedor extends ModelBase
{
    protected $table = 'fornecedor';
    protected $connection = 'mercado';
    protected $fillable = [
        'empresa_master_cod',
        'nome',
        'nome_fantasia',
        'documento',
        'pessoa',
        'ativo',
        'celular',
        'telefone_fixo',
        'email',
        'site',
        'endereco_id'
    ];

    public function endereco()
    {
        return $this->belongsTo(Endereco::class);
    }

    public function nomeFormatado()
    {
        return $this->nome .' - '. aplicarMascaraDocumento($this->documento);
    }
}
