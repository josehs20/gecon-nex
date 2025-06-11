<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class AdicionarItemTempRequest extends ServiceUseCase
{
    // 1. Declare as propriedades da classe
    public function __construct(
        private int $estoqueId, // Propriedade declarada e inicializada aqui
        private float $quantidade, // Propriedade declarada e inicializada aqui
        private int $caixaId, // Propriedade declarada e inicializada aqui
        CriarHistoricoRequest $historicoRequest // Parâmetro existente
    ) {
        // Chama o construtor da classe pai
        parent::__construct($historicoRequest);
        $this->estoqueId = $estoqueId;
        $this->quantidade = $quantidade;
        $this->caixaId = $caixaId;
    }

    // --- Métodos Getters ---

    /**
     * Get the value of estoqueId
     */
    public function getEstoqueId(): int
    {
        return $this->estoqueId;
    }

    /**
     * Get the value of quantidade
     */
    public function getQuantidade(): float
    {
        return $this->quantidade;
    }

    /**
     * Get the value of caixaId
     */
    public function getCaixaId(): int
    {
        return $this->caixaId;
    }

    // --- Métodos Setters ---

    /**
     * Set the value of estoqueId
     *
     * @param int $estoqueId
     * @return self
     */
    public function setEstoqueId(int $estoqueId): self
    {
        $this->estoqueId = $estoqueId;
        return $this; // Permite method chaining
    }

    /**
     * Set the value of quantidade
     *
     * @param float $quantidade
     * @return self
     */
    public function setQuantidade(float $quantidade): self
    {
        $this->quantidade = $quantidade;
        return $this; // Permite method chaining
    }

    /**
     * Set the value of caixaId
     *
     * @param int $caixaId
     * @return self
     */
    public function setCaixaId(int $caixaId): self
    {
        $this->caixaId = $caixaId;
        return $this; // Permite method chaining
    }
}
