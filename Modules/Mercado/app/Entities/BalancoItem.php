<?php

namespace Modules\Mercado\Entities;
class BalancoItem extends ModelBase
{
    protected $table = 'balanco_item';
    protected $connection = 'mercado';
    protected $fillable = [
        'estoque_id',
        'loja_id',
        'quantidade_estoque_sistema',
        'quantidade_estoque_real',
        'quantidade_resultado_operacional',
        'balanco_id',
        'ativo',
        'tipo_movimentacao_estoque_id'
    ];

    public function loja(){
        return $this->belongsTo(Loja::class, 'loja_id');
    }

    public function status(){
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function balanco(){
        return $this->belongsTo(Balanco::class);
    }

    public function estoque(){
        return $this->belongsTo(Estoque::class, 'estoque_id');
    }

    public function tipo_movimentacao(){
        return $this->belongsTo(TipoMovimentacaoEstoque::class, 'tipo_movimentacao_estoque_id');
    }
}
