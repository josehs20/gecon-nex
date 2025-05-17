<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Balanco\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class BalancoItemRequest extends ServiceUseCase
{
    private int $estoque_id;
    private int $loja_id;
    private float $quantidade_estoque_sistema;
    private float $quantidade_estoque_real;
    private float $quantidade_resultado_operacional;
    private int $balanco_id;
    private int $ativo;
    private int $tipo_movimentacao_id;

    public function __construct(
        int $estoque_id,
        int $loja_id,
        float $quantidade_estoque_sistema,
        float $quantidade_estoque_real,
        float $quantidade_resultado_operacional,
        int $balanco_id,
        int $ativo,
        int $tipo_movimentacao_id,
        CriarHistoricoRequest $historicoRequest
    ) {
        parent::__construct($historicoRequest);
        $this->estoque_id = $estoque_id;
        $this->loja_id = $loja_id;
        $this->quantidade_estoque_sistema = $quantidade_estoque_sistema;
        $this->quantidade_estoque_real = $quantidade_estoque_real;
        $this->quantidade_resultado_operacional = $quantidade_resultado_operacional;
        $this->balanco_id = $balanco_id;
        $this->setAtivo($ativo);
        $this->tipo_movimentacao_id = $tipo_movimentacao_id;
    }


    public function getTipoMovimentacaoId(): int
    {
        return $this->tipo_movimentacao_id;
    }

    public function getEstoqueId(): int
    {
        return $this->estoque_id;
    }

    public function getLojaId(): int
    {
        return $this->loja_id;
    }

    public function getQuantidadeEstoqueSistema(): float
    {
        return $this->quantidade_estoque_sistema;
    }


    public function getQuantidadeEstoqueReal(): float
    {
        return $this->quantidade_estoque_real;
    }


    public function getQuantidadeResultadoOperacional(): float
    {
        return $this->quantidade_resultado_operacional;
    }


    public function getBalancoId(): int
    {
        return $this->balanco_id;
    }

    public function getAtivo(): int
    {
        return $this->ativo;
    }


    public function setAtivo(int $ativo): void
    {
        if ($ativo !== 1 && $ativo !== 0) {
            throw new \Exception("O valor de ativo deve ser 1 ou 0.");
        }
        $this->ativo = $ativo;
    }
}
