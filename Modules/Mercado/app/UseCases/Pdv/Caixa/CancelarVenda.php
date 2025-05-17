<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Exception;
use Modules\Mercado\Application\CaixaApplication;
use Modules\Mercado\Repository\Venda\VendaRepository;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\CancelarVendaRequest;

class CancelarVenda
{
    private CancelarVendaRequest $request;
    public function __construct(CancelarVendaRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $this->validate();
        $this->excluiVendaSalva();
        $caixa = $this->atualizaStatusCaixa();
        $caixa->status;
        return $caixa;
    }

    private function validate() {}

    private function excluiVendaSalva()
    {
        $venda = VendaRepository::getVendaByNVenda($this->request->getEditarStatusCaixa()->getId(), $this->request->getNvenda());
        $deletou = VendaRepository::deleteVenda($venda->id, $this->request->getEditarStatusCaixa()->getCriarHistoricoRequest());

        if (!$deletou) {
            throw new Exception("Erro ao excluir venda", 1);
        }
    }

    private function atualizaStatusCaixa()
    {
        return CaixaApplication::editar_status($this->request->getEditarStatusCaixa());
    }
}
