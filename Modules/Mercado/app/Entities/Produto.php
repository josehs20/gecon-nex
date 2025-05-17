<?php

namespace Modules\Mercado\Entities;
class Produto extends ModelBase
{
    protected $table = 'produtos';
    protected $connection = 'mercado';
    protected $fillable = [
        'nome',
        'loja_id',
        'descricao',
        'cod_barras',
        'cod_aux',
        'unidade_medida_id',
        'classificacao_produto_id',
        'fabricante_id',
        'data_validade',
        'descricao',
        'link_foto',
    ];

    public function estoques()
    {
        return $this->hasMany(Estoque::class, 'produto_id');
    }

    public function estoque()
    {
        return $this->hasOne(Estoque::class, 'produto_id')->where('loja_id', auth()->user()->getUserModulo->loja_id);
    }

    // public function loja()
    // {
    //     return $this->hasOneThrough(
    //         Loja::class,
    //         LojaProduto::class,
    //         'produto_id', // Foreign key on LojaProduto table...
    //         'id', // Foreign key on Loja table...
    //         'id', // Local key on Produto table...
    //         'loja_id' // Local key on LojaProduto table...
    //     )->where('loja_id', auth()->user()->usuarioMercado->loja_id);
    // }

    public function lojas()
    {
        return $this->hasManyThrough(
            Loja::class,      // Modelo final
            Estoque::class,   // Modelo intermediário
            'produto_id',     // Chave estrangeira no modelo Estoque
            'id',             // Chave estrangeira no modelo Loja (normalmente 'id')
            'id',             // Chave local no modelo Produto
            'loja_id'         // Chave local no modelo Estoque que referencia Loja
        )->whereIn('lojas.id',  auth()->user()->usuarioMercado->lojas->pluck('id')); //garante que só pega lojas que o usuario tem permissão
    }
    public function lojas_produto()
    {
        return $this->hasMany(LojaProduto::class, 'produto_id', 'id');
    }

    // public function loja_produto()
    // {
    //     return $this->belongsToMany(Loja::class, 'lojas_produto', 'produto_id', 'loja_id')->where('loja_id', auth()->user()->usuarioMercado->loja_id);
    // }

    public function fabricante()
    {
        return $this->belongsTo(Fabricante::class, 'fabricante_id');
    }

    public function unidade_medida()
    {
        return $this->belongsTo(UnidadeMedida::class);
    }

    public function classificacao_produto()
    {
        return $this->belongsTo(ClassificacaoProduto::class, 'classificacao_produto_id');
    }

    public function custo()
    {
        return converterParaReais($this->custo);
    }

    public function preco()
    {
        return converterParaReais($this->preco);
    }

    public function dataValidade()
    {
        return  date('d/m/Y', strtotime($this->data_validade));
    }

    public function movimentacao_estoque()
    {
        return $this->hasMany(MovimentacaoEstoque::class, 'produto_id');
    }

    public function getNomeCompleto()
    {
        return $this->nome . ' ' . $this->unidade_medida->sigla . ' - '. $this->fabricante->nome;
    }
}
