<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Exception;
use Modules\Mercado\Application\CaixaApplication;
use Modules\Mercado\Application\VendaApplication;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\EditarStatusCaixaRequest;
use Modules\Mercado\UseCases\Pdv\Venda\Requests\AtualizaVendaRequest;
use Modules\Mercado\UseCases\Pdv\Venda\Requests\CriarVendaRequest;

class SalvarVenda
{
    private CriarVendaRequest $request;
    public function __construct(CriarVendaRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $this->validade();
        $venda = $this->salvaVenda();
   
        $this->atualizarStatusCaixa(config('config.status.livre'));
        return $venda;
    }

    private function validade()
    {
        if ($this->request->getItens() == 0) {
            throw new Exception("Nenhum item a ser salvo!.", 1);
        }
        if (!$this->request->getClienteId()) {
            throw new Exception("Nenhum cliente informado!.", 1);
        }
    }

    private function salvaVenda()
    {
        if ($this->request->getVendaId() != null) {
            return VendaApplication::atualizaVenda(new AtualizaVendaRequest($this->request->getVendaId(), $this->request));
        } else {
            return VendaApplication::criarVenda($this->request);
        }
    }

    private function atualizarStatusCaixa($status_id)
    {
        return CaixaApplication::editar_status(new EditarStatusCaixaRequest(
            $this->request->getCriarHistoricoRequest(),
            $this->request->getCaixaId(),
            $status_id,
            $this->request->getUsuarioId()
        ));
    }
}
