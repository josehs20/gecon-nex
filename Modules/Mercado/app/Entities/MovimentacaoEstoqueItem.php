<?php

namespace Modules\Mercado\Entities;

class MovimentacaoEstoqueItem extends ModelBase
{
    protected $table = 'movimentacao_estoque_item';
    protected $connection = 'mercado';
    protected $fillable = [
        'estoque_id',
        'quantidade_movimentada',
        'tipo_movimentacao_estoque_id',
        'movimentacao_id',
        'ativo'
    ];

    public function estoque(){
        return $this->belongsTo(Estoque::class, 'estoque_id');
    }

    public function tipo_movimentacao(){
        return $this->belongsTo(TipoMovimentacaoEstoque::class, 'tipo_movimentacao_estoque_id');
    }

    public function movimentacao(){
        return $this->belongsTo(MovimentacaoEstoque::class, 'movimentacao_id');
    }
}
