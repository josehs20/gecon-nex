<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Balanco;

use Modules\Mercado\Repository\Balanco\BalancoRepository;
use Modules\Mercado\UseCases\Gerenciamento\Balanco\Requests\BalancoItemRequest;

class CriarBalancoItem
{
    private BalancoItemRequest $request;
    public function __construct(BalancoItemRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $balancoItem = $this->getBalancoItem();
        if (!$balancoItem) {
            $balancoItem = $this->createBalancoItem();
        } else {
            $balancoItem = $this->updateBalancoItem($balancoItem);
        }
        return $balancoItem;
    }

    private function getBalancoItem()
    {
        return BalancoRepository::verificarExistenciaProdutoBalancoItem(
            $this->request->getBalancoId(),
            $this->request->getEstoqueId(),
            function($query){
                $query->withTrashed();
            }
        );
    }

    private function createBalancoItem()
    {
        return BalancoRepository::createBalancoItem(
            $this->request->getEstoqueId(),
            $this->request->getLojaId(),
            $this->request->getQuantidadeEstoqueSistema(),
            $this->request->getQuantidadeEstoqueReal(),
            $this->request->getQuantidadeResultadoOperacional(),
            $this->request->getBalancoId(),
            $this->request->getAtivo(),
            $this->request->getTipoMovimentacaoId(),
            $this->request->getCriarHistoricoRequest()
        );
    }

    private function updateBalancoItem($balancoItem)
    {
        return BalancoRepository::updateBalancoItem(
            $balancoItem->id,
            $this->request->getEstoqueId(),
            $this->request->getLojaId(),
            $this->request->getQuantidadeEstoqueSistema(),
            $this->request->getQuantidadeEstoqueReal(),
            $this->request->getQuantidadeResultadoOperacional(),
            $this->request->getBalancoId(),
            $this->request->getAtivo(),
            $this->request->getTipoMovimentacaoId(),
            $this->request->getCriarHistoricoRequest()
        );
    }
}
