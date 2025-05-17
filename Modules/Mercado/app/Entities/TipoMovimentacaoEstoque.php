<?php

namespace Modules\Mercado\Entities;

class TipoMovimentacaoEstoque extends ModelBase
{
    protected $table = 'tipo_movimentacao_estoque';
    protected $connection = 'mercado';
    protected $fillable = ['descricao'];


    public function loja()
    {
        return $this->belongsTo(Loja::class);
    }
}
