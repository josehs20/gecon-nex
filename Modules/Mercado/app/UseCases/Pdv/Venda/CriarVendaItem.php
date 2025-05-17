<?php

namespace Modules\Mercado\UseCases\Pdv\Venda;

use Modules\Mercado\Repository\VendaItem\VendaItemRepository;
use Modules\Mercado\UseCases\Pdv\Venda\Requests\CriarVendaItemRequest;

class CriarVendaItem
{
    private CriarVendaItemRequest $request;

    public function __construct(CriarVendaItemRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        return $this->criarVendaItem();
    }

    private function criarVendaItem()
    {

        return VendaItemRepository::create(
            $this->request->getCriarHistoricoRequest(),
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
