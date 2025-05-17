<?php

namespace Modules\Mercado\Entities;

class Venda extends ModelBase
{
    protected $table = 'vendas';
    protected $connection = 'mercado';
    protected $fillable = [
        'n_venda',
        'cliente_id',
        'caixa_id',
        'caixa_evidencia_id',
        'loja_id',
        'usuario_id',
        'status_id',
        'forma_pagamento_id',
        'sub_total',
        'total',
        'desconto_porcentagem',
        'desconto_dinheiro',
        'data_concluida',
    ];

    public function venda_itens()
    {
        return $this->hasMany(VendaItem::class, 'venda_id', 'id');
    }

    public function venda_pagamentos()
    {
        return $this->hasMany(VendaPagamento::class, 'venda_id', 'id');
    }

    public function pagamentos()
    {
        return $this->hasMany(Pagamento::class, 'venda_id', 'id');
    }

    public function caixa()
    {
        return $this->belongsTo(Caixa::class, 'caixa_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'cliente_id');
    }

    public function devolucoes()
    {
        return $this->hasMany(Devolucao::class, 'venda_id');
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class, 'loja_id');
    }

    public function devolucao_itens()
    {
        return $this->hasMany(DevolucaoItem::class, 'venda_id');
    }

    public function getMethodNFCE()
    {
        switch ($this->pagamentos->first()->especie) {
            case $this->pagamentos->first()->especie_pagamento_id == config('config.especie_pagamento.pix.id'):
                return 'InstantPayment';
                break;
            case $this->pagamentos->first()->especie_pagamento_id == config('config.especie_pagamento.cartao_credito.id'):
                return 'InstantPayment';
                break;
            case $this->pagamentos->first()->especie_pagamento_id == config('config.especie_pagamento.cartao_debito.id'):
                return 'InstantPayment';
                break;
            case $this->pagamentos->first()->especie_pagamento_id == config('config.especie_pagamento.dinheiro.id'):
                return 'Cash';
                break;
            case $this->pagamentos->first()->especie_pagamento_id == config('config.especie_pagamento.credito_loja.id'):
                return 'Cash';
                break;
            default:
                return 'Cash';
                break;
        }
    }

    public function getPaymentTypeNFCE()
    {
        switch ($this->pagamentos->first()->especie) {
            case $this->pagamentos->first()->especie_pagamento_id == config('config.especie_pagamento.pix.id'):
                return 'InCash';
                break;
            case $this->pagamentos->first()->especie_pagamento_id == config('config.especie_pagamento.dinheiro.id'):
                return 'InCash';
                break;
            default:
                return 'Term';
                break;
        }
    }
}
