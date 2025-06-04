<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class SangriaRequest extends ServiceUseCase
{
    private int $caixa_id;
    private string $descricao;
    private int $valorSangria;
    private int $especie_pagamento_id;

    // Construtor para inicializar os campos
    public function __construct(CriarHistoricoRequest $historicoRequest, int $caixa_id, $valorSangria, int $especie_pagamento_id, string $descricao)
    {
        parent::__construct($historicoRequest);
        $this->caixa_id = $caixa_id;
        $this->especie_pagamento_id = $especie_pagamento_id;
        $this->valorSangria = $valorSangria;
        $this->descricao = $descricao;
        $this->especie_pagamento_id = $especie_pagamento_id;
    }

        public function getEspeciePagamentoId(): int
    {
        return $this->especie_pagamento_id;
    }


    // Método get para caixa_id
    public function getCaixaId(): int
    {
        return $this->caixa_id;
    }

    // Método set para caixa_id
    public function setCaixaId(int $caixa_id): void
    {
        $this->caixa_id = $caixa_id;
    }

    public function getValorSangria(): ?int
    {
        return $this->valorSangria;
    }

    public function setValorSangria(?int $valorSangria = null): void
    {
        $this->valorSangria = $valorSangria;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(?string $descricao = null): void
    {
        $this->descricao = $descricao;
    }

}
