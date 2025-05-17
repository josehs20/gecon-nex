<?php

namespace Modules\Mercado\UseCases\Pdv\Venda;

use Modules\Mercado\Repository\VendaItem\VendaItemRepository;
use Modules\Mercado\UseCases\Pdv\Venda\Requests\AtualizaVendaItemRequest;

class AtualizaVendaItem
{
    private AtualizaVendaItemRequest $request;

    public function __construct(AtualizaVendaItemRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        return $this->atualizaVendaItem();
    }

    private function atualizaVendaItem()
    {
        return VendaItemRepository::update(
            $this->request->getCriarHistoricoRequest(),
            $this->request->getId(),
            $this->request->getVendaId(),
            $this->request->getCaixaId(),
            $this->request->getCaixaEvidenciaId(),
            $this->request->getLojaId(),
            $this->request->getEstoqueId(),
            $this->request->getProdutoId(),
            $this->request->getQuantidade(),
            $this->request->getPreco(),
            $this->request->getTotal()
        );
    }
}
