<?php

namespace Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class MovimentacaoEstoqueItemRequest extends ServiceUseCase
{
    private int $estoque_id;
    private int $movimentacao_estoque_id;
    private int $tipo_movimentacao_id;
    private float $quantidade;

    public function __construct(int $estoque_id, int $movimentacao_estoque_id, int $tipo_movimentacao_id, float $quantidade, CriarHistoricoRequest $criarHistoricoRequest)
    {
        parent::__construct($criarHistoricoRequest);
        $this->setEstoqueId($estoque_id);
        $this->setMovimentacaoEstoqueId($movimentacao_estoque_id);
        $this->setTipoMovimentacao($tipo_movimentacao_id);
        $this->setQuantidade($quantidade);
    }


    public function getEstoqueId(): int
    {
        return $this->estoque_id;
    }

    public function setEstoqueId(int $estoque_id): void
    {
        $this->estoque_id = $estoque_id;
    }

    public function getMovimentacaoEstoqueId(): int
    {
        return $this->movimentacao_estoque_id;
    }

    public function setMovimentacaoEstoqueId(int $movimentacao_estoque_id): void
    {
        $this->movimentacao_estoque_id = $movimentacao_estoque_id;
    }

    public function getTipoMovimentacao(): int
    {
        return $this->tipo_movimentacao_id;
    }

    public function setTipoMovimentacao($tipo_movimentacao): void
    {
        $this->tipo_movimentacao_id = $tipo_movimentacao;
    }

    public function getQuantidade(): float
    {
        return $this->quantidade;
    }

    public function setQuantidade(float $quantidade): void
    {
        $this->quantidade = $quantidade;
    }
}
