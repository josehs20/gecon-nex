<?php

namespace Modules\Mercado\UseCases\Gerenciamento\UnidadeMedida\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class EditarUnidadeMedidaRequest extends CriarUnidadeMedidaRequest
{
    private int $id;

    // Constructor to initialize properties
    public function __construct(CriarHistoricoRequest $criarHistoricoRequest, int $id, string $nome, string $sigla, bool $podeSerFracionado, int $empresaId)
    {
        parent::__construct($criarHistoricoRequest, $nome, $sigla, $podeSerFracionado, $empresaId);
        $this->id = $id;
    }

    // Getters and Setters
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
