<?php

namespace Modules\Mercado\Entities;
class MovimentacaoEstoque extends ModelBase
{
    protected $table = 'movimentacao_estoque';
    protected $connection = 'mercado';
    protected $fillable = [
        'loja_id',
        'status_id',
        'usuario_id',
        'tipo_movimentacao_estoque_id',
        'observacao',
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

    public function tipo_movimentacao_estoque()
    {
        return $this->belongsTo(TipoMovimentacaoEstoque::class, 'tipo_movimentacao_estoque_id');
    }

    public function movimentacao_estoque_itens()
    {
        return $this->hasMany(MovimentacaoEstoqueItem::class, 'movimentacao_id', 'id');
    }
}
