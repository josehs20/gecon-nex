<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Pagamento\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class ReceberVendaRequest extends ServiceUseCase
{
    protected CriarHistoricoRequest $criarHistoricoRequest;
    protected array $venda_pagamentos;
    protected array $forma_pagamentos;
    protected int $caixa_id;
    protected int $caixa_evidencia_id;
    protected int $loja_id;

    // Construtor
    public function __construct(CriarHistoricoRequest $criarHistoricoRequest, int $caixa_id, int $caixa_evidencia_id, int $loja_id, array $venda_pagamentos, array $forma_pagamentos)
    {
        parent::__construct($criarHistoricoRequest);
        $this->venda_pagamentos = $venda_pagamentos;
        $this->forma_pagamentos = $forma_pagamentos;
        $this->caixa_id = $caixa_id;
        $this->caixa_evidencia_id = $caixa_evidencia_id;
        $this->loja_id = $loja_id;
    }

    // Para $venda_pagamentos
    public function getVendaPagamentos(): array
    {
        return $this->venda_pagamentos;
    }

    public function setVendaPagamentos(array $venda_pagamentos): void
    {
        $this->venda_pagamentos = $venda_pagamentos;
    }

    // Para $forma_pagamentos
    public function getFormaPagamentos(): array
    {
        return $this->forma_pagamentos;
    }

    public function setFormaPagamentos(array $forma_pagamentos): void
    {
        $this->forma_pagamentos = $forma_pagamentos;
    }

    // Método get para $caixa_id
    public function getCaixaId(): int
    {
        return $this->caixa_id;
    }

    // Método set para $caixa_id
    public function setCaixaId(int $caixa_id): void
    {
        $this->caixa_id = $caixa_id;
    }

    // Método get para $caixa_evidencia_id
    public function getCaixaEvidenciaId(): int
    {
        return $this->caixa_evidencia_id;
    }

    // Método set para $caixa_evidencia_id
    public function setCaixaEvidenciaId(int $caixa_evidencia_id): void
    {
        $this->caixa_evidencia_id = $caixa_evidencia_id;
    }

    public function getLojaId(): int
    {
        return $this->loja_id;
    }

    // Método set para $loja_id
    public function setLojaId(int $loja_id): void
    {
        $this->loja_id = $loja_id;

    }
}
