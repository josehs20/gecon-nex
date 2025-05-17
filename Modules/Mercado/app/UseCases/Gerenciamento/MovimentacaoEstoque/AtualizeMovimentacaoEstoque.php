<?php

namespace Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque;

use Modules\Mercado\Repository\MovimentacaoEstoque\MovimentacaoEstoqueRepository;
use Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque\Requests\MovimentacaoEstoqueRequest;

class AtualizeMovimentacaoEstoque
{
    private int $id;
    private MovimentacaoEstoqueRequest $request;

    public function __construct(int $id, MovimentacaoEstoqueRequest $request)
    {
        $this->request = $request;
        $this->id = $id;
    }

    public function handle()
    {
        return $this->atualizaMovimentacaoEstoque();
    }

    public function atualizaMovimentacaoEstoque(){

        return MovimentacaoEstoqueRepository::atualizaMovimentacaoEstoque(
            $this->id,
            $this->request->getLojaId(),
            $this->request->getStatusId(),
            $this->request->getUsuarioId(),
            $this->request->getTipoMovimentacao(),
            $this->request->getCriarHistoricoRequest(),
            $this->request->getObservacao(),
        );
    }
}
