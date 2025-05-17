<?php

namespace Modules\Mercado\UseCases\Pdv\Venda\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class CriarVendaItemRequest extends ServiceUseCase
{
    private int $venda_id;
    private int $estoque_id;
    private int $caixa_evidencia_id;
    private int $caixa_id;
    private int $loja_id;
    private int $produto_id;
    private float $quantidade;
    private float $preco;
    private float $total;

    // Construtor para inicializar os campos
    public function __construct(
        CriarHistoricoRequest $criarHistoricoRequest,
        int $venda_id,
        int $caixa_id,
        int $caixa_evidencia_id,
        int $loja_id,
        int $estoque_id,
        int $produto_id,
        float $quantidade,
        float $preco,
        float $total
    ) {
        parent::__construct($criarHistoricoRequest);
        $this->venda_id = $venda_id;
        $this->loja_id = $loja_id;
        $this->caixa_evidencia_id = $caixa_evidencia_id;
        $this->caixa_id = $caixa_id;
        $this->estoque_id = $estoque_id;
        $this->produto_id = $produto_id;
        $this->quantidade = $quantidade;
        $this->preco = $preco;
        $this->total = $total;
    }

    // Getters e Setters para venda_id
    public function getVendaId(): int
    {
        return $this->venda_id;
    }

    public function setVendaId(int $venda_id): void
    {
        $this->venda_id = $venda_id;
    }

    // Getters e Setters para estoque_id
    public function getEstoqueId(): int
    {
        return $this->estoque_id;
    }

    public function setEstoqueId(int $estoque_id): void
    {
        $this->estoque_id = $estoque_id;
    }

    // Getters e Setters para produto_id
    public function getProdutoId(): int
    {
        return $this->produto_id;
    }

    public function setProdutoId(int $produto_id): void
    {
        $this->produto_id = $produto_id;
    }

    // Getters e Setters para quantidade
    public function getQuantidade(): float
    {
        return $this->quantidade;
    }

    public function setQuantidade(float $quantidade): void
    {
        $this->quantidade = $quantidade;
    }

    // Getters e Setters para preco
    public function getPreco(): float
    {
        return $this->preco;
    }

    public function setPreco(float $preco): void
    {
        $this->preco = $preco;
    }

    // Getters e Setters para total
    public function getTotal(): float
    {
        return $this->total;
    }

    public function setTotal(float $total): void
    {
        $this->total = $total;
    }

    public function getCaixaId(): int
    {
        return $this->caixa_id;
    }

    public function setCaixaId(int $caixa_id): void
    {
        $this->caixa_id = $caixa_id;
    }

    public function getCaixaEvidenciaId(): int
    {
        return $this->caixa_evidencia_id;
    }

    public function setCaixaEvidenciaId(int $caixa_evidencia_id): void
    {
        $this->caixa_evidencia_id = $caixa_evidencia_id;
    }
    
    public function getLojaId(): int
    {
        return $this->loja_id;
    }

    public function setLojaId(int $loja_id): void
    {
        $this->loja_id = $loja_id;
    }
}
