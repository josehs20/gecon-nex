<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Estoque\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class CriarEstoqueRequest
{
    private float $custo;
    private float $preco;
    private float $quantidade_total;
    private float $quantidade_disponivel;
    private float $quantidade_minima;
    private float $quantidade_maxima;
    private ?string $localizacao;
    private int $produto_id;
    private int $loja_id;
    private CriarHistoricoRequest $historicoRequest;

    // Construtor
    public function __construct($custo, $preco, $produto_id, $loja_id, $quantidade_total = null, $quantidade_disponivel = null, $quantidade_minima = null, $quantidade_maxima = null, $localizacao = null, CriarHistoricoRequest $historicoRequest)
    {
        $this->custo = $custo;
        $this->preco = $preco;
        $this->quantidade_total = $quantidade_total;
        $this->quantidade_disponivel = $quantidade_disponivel;
        $this->quantidade_minima = $quantidade_minima;
        $this->quantidade_maxima = $quantidade_maxima;
        $this->localizacao = $localizacao;
        $this->produto_id = $produto_id;
        $this->loja_id = $loja_id;
        $this->historicoRequest = $historicoRequest;
    }

    // Getters
    public function getQuantidadeTotal()
    {
        return $this->quantidade_total;
    }

    public function getQuantidadeDisponivel()
    {
        return $this->quantidade_disponivel;
    }

    public function getQuantidadeMinima()
    {
        return $this->quantidade_minima;
    }

    public function getQuantidadeMaxima()
    {
        return $this->quantidade_maxima;
    }

    public function getLocalizacao()
    {
        return $this->localizacao;
    }

    public function getLojaId()
    {
        return $this->loja_id;
    }

    public function getProdutoId()
    {
        return $this->produto_id;
    }

    public function getPreco()
    {
        return $this->preco;
    }

    public function getCusto()
    {
        return $this->custo;
    }

    public function getHistoricoRequest()
    {
        return $this->historicoRequest;
    }

    // Setters
    public function setQuantidadeTotal($quantidade_total)
    {
        $this->quantidade_total = $quantidade_total;
    }

    public function setQuantidadeDisponivel($quantidade_disponivel)
    {
        $this->quantidade_disponivel = $quantidade_disponivel;
    }

    public function setQuantidadeMinima($quantidade_minima)
    {
        $this->quantidade_minima = $quantidade_minima;
    }

    public function setQuantidadeMaxima($quantidade_maxima)
    {
        $this->quantidade_maxima = $quantidade_maxima;
    }

    public function setLocalizacao($localizacao)
    {
        $this->localizacao = $localizacao;
    }

    public function setLojaId($loja_id)
    {
        $this->loja_id = $loja_id;
    }
    public function setProdutoId($produto_id)
    {
        $this->produto_id = $produto_id;
    }

    public function setCusto($custo)
    {
        $this->custo = $custo;
    }

    public function setPreco($preco)
    {
        $this->preco = $preco;
    }

    public function setHistoricoRequest($historicoRequest)
    {
        $this->historicoRequest = $historicoRequest;
    }

}
