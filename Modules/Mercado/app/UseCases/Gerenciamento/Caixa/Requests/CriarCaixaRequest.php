<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Caixa\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class CriarCaixaRequest extends ServiceUseCase
{
    private int $status_id;
    private string $nome;
    private array $lojas;

    public function __construct(CriarHistoricoRequest $historicoRequest, string $nome, int $status_id, array $lojas)
    {
        parent::__construct($historicoRequest);
        $this->nome = $nome;
        $this->status_id = $status_id;
        $this->lojas = $lojas;
    }

    public function getLojas(): array
    {
        return $this->lojas;
    }

    public function setLojas(array $lojas)
    {
        $this->lojas = $lojas;
    }

    public function getStatus(): int
    {
        return $this->status_id;
    }

    public function setStatus(int $status_id)
    {
        $this->status_id = $status_id;
    }
    public function getNome(): string
    {
        return $this->nome;
    }

    public function setnome(string $nome)
    {
        $this->nome = $nome;
    }
}
