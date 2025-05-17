<?php

namespace Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque;

use Modules\Mercado\Repository\MovimentacaoEstoque\MovimentacaoEstoqueRepository;
use Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque\Requests\MovimentacaoEstoqueRequest;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class CriarMovimentacaoEstoque
{
    private MovimentacaoEstoqueRequest $request;

    public function __construct(MovimentacaoEstoqueRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        return $this->criarMovimentacaoEstoque();
    }

    public function criarMovimentacaoEstoque(){

        return MovimentacaoEstoqueRepository::createMovimentacaoEstoque(
            $this->request->getLojaId(),
            $this->request->getStatusId(),
            $this->request->getUsuarioId(),
            $this->request->getTipoMovimentacao(),
            $this->request->getCriarHistoricoRequest(),
            $this->request->getObservacao()
        );
    }
}
