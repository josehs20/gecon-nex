<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class FinalizarVendaRequest extends ServiceUseCase
{
    private int $caixa_id;
    private int $cliente_id;
    private array $formas_pagamento;
    private ?float $desconto_percentual;
    // private ?CriarVendaRequest $vendaRequest = null;

    public function __construct(
        CriarHistoricoRequest $criarHistoricoRequest,
        int $caixa_id,
        int $cliente_id,
        array $formas_pagamento,
        ?float $desconto_percentual = null
    ) {
        parent::__construct($criarHistoricoRequest);

        $this->caixa_id = $caixa_id;
        $this->cliente_id = $cliente_id;
        $this->formas_pagamento = $formas_pagamento;
        $this->desconto_percentual = $desconto_percentual;
    }

    // // Get e Set para vendaRequest
    // public function getVendaRequest(): ?CriarVendaRequest
    // {
    //     return $this->vendaRequest;
    // }

    // public function setVendaRequest(CriarVendaRequest $vendaRequest): void
    // {
    //     $this->vendaRequest = $vendaRequest;
    // }

    public function getDesconto(): ?float
    {
        return $this->desconto_percentual;
    }

    // Getters para caixa_id
    public function getCaixaId(): int
    {
        return $this->caixa_id;
    }

    // Getters para cliente_id
    public function getClienteId(): int
    {
        return $this->cliente_id;
    }

    // Getters para formas_pagamento
    public function getFormasPagamento(): array
    {
        return $this->formas_pagamento;
    }

    // Setters caso precise alterar os valores depois
    public function setCaixaId(int $caixa_id): void
    {
        $this->caixa_id = $caixa_id;
    }

    public function setClienteId(int $cliente_id): void
    {
        $this->cliente_id = $cliente_id;
    }

    public function setFormasPagamento(array $formas_pagamento): void
    {
        $this->formas_pagamento = $formas_pagamento;
    }
}
