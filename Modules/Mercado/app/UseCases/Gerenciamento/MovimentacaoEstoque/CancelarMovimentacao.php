<?php

namespace Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque;

use Modules\Mercado\Repository\MovimentacaoEstoque\MovimentacaoEstoqueRepository;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class CancelarMovimentacao
{
    private int $id;
    private CriarHistoricoRequest $criarHistoricoRequest;

    public function __construct(int $id, CriarHistoricoRequest $criarHistoricoRequest)
    {
        $this->criarHistoricoRequest = $criarHistoricoRequest;
        $this->id = $id;
    }

    public function handle()
    {
        return $this->deleteMovimentacao();
    }

    public function deleteMovimentacao()
    {
        return MovimentacaoEstoqueRepository::atualizarStatusMovimentacaoEstoque(
            $this->id,
            config('config.status.cancelado'),
            $this->criarHistoricoRequest
        );
    }
}
