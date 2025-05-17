<?php

namespace Modules\Mercado\Entities;


class CotacaoFornecedor extends ModelBase
{
    protected $table = 'cotacao_fornecedores';
    protected $connection = 'mercado';
    protected $fillable = [
        'cotacao_id',
        'loja_id',
        'fornecedor_id',
        'desconto',
        'total',
        'sub_total',
        'frete',
        'observacao',
        'previsao_entrega'
    ];

    public function cot_for_itens()
    {
        return $this->hasMany(CotacaoFornecedorItem::class, 'cotacao_fornecedor_id', 'id');
    }

    public function cotacao()
    {
        return $this->belongsTo(Cotacao::class, 'cotacao_id');
    }

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class, 'fornecedor_id');
    }

    public function desconto()
    {
        return $this->desconto ? converteCentavosParaFloat($this->desconto) : 0;
    }

    public function frete()
    {
        return $this->frete ? converteCentavosParaFloat($this->frete) : 0;
    }
}
