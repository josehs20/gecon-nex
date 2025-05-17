<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Estoque\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class UpdateQtdDisponivelRequest
{
    private int $id;
    private float $quantidade_disponivel;
    private float $quantidade_total;
    private CriarHistoricoRequest $historicoRequest;

    // Construtor
    public function __construct(int $id, float $quantidade_disponivel, float $quantidade_total, CriarHistoricoRequest $historicoRequest)
    {
        $this->setId($id);
        $this->setQuantidadeDisponivel($quantidade_disponivel);
        $this->setQtdTotal($quantidade_total);
        $this->setHistoricoRequest($historicoRequest);

    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getQuantidadeDisponivel(): float
    {
        return $this->quantidade_disponivel;
    }

    public function setQuantidadeDisponivel(float $quantidade_disponivel): void
    {
        $this->quantidade_disponivel = $quantidade_disponivel;
    }

    public function getHistoricoRequest(): CriarHistoricoRequest
    {
        return $this->historicoRequest;
    }

    public function setHistoricoRequest(CriarHistoricoRequest $historicoRequest): void
    {
        $this->historicoRequest = $historicoRequest;
    }

    public function setQtdTotal(float $quantidade_total): void
    {
        $this->quantidade_total = $quantidade_total;
    }

    public function getQtdTotal()
    {
        return $this->quantidade_total;
    }
}
