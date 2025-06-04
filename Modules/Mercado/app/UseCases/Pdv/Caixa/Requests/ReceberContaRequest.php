<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class ReceberContaRequest extends ServiceUseCase
{
    private int $loja_id;
    private int $caixa_id;
    private int $usuario_id;
    private int $forma_pagamento;
    private ?int $observacao;
    private array $venda_parcelas; // contendo a parcela e quanto foi paga dela

    public function __construct(
        CriarHistoricoRequest $criarHistoricoRequest,
        int $loja_id,
        int $caixa_id,
        int $usuario_id,
        array $venda_parcelas,
        int $forma_pagamento,
        ?int $observacao = null
    ) {
        parent::__construct($criarHistoricoRequest);
        $this->loja_id = $loja_id;
        $this->caixa_id = $caixa_id;
        $this->usuario_id = $usuario_id;
        $this->observacao = $observacao;
        $this->forma_pagamento = $forma_pagamento;
        $this->venda_parcelas = $venda_parcelas;
    }

    public function getFormaPagamento(): int
    {
        return $this->forma_pagamento;
    }

    public function getLojaId(): int
    {
        return $this->loja_id;
    }

    public function setLojaId(int $loja_id): void
    {
        $this->loja_id = $loja_id;
    }

    public function getCaixaId(): int
    {
        return $this->caixa_id;
    }

    public function setCaixaId(int $caixa_id): void
    {
        $this->caixa_id = $caixa_id;
    }

    public function getUsuarioId(): int
    {
        return $this->usuario_id;
    }

    public function setUsuarioId(int $usuario_id): void
    {
        $this->usuario_id = $usuario_id;
    }

    public function getObservacao(): ?int
    {
        return $this->observacao;
    }

    public function setObservacao(?int $observacao): void
    {
        $this->observacao = $observacao;
    }

    public function getVendaParcelas(): array
    {
        return $this->venda_parcelas;
    }

    public function setvendaParcelas(array $venda_parcelas): void
    {
        $this->venda_parcelas = $venda_parcelas;
    }
}
