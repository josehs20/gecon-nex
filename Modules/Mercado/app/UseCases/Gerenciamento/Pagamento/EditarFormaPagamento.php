<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Pagamento;

use Modules\Mercado\Repository\Pagamento\PagamentoRepository;
use Modules\Mercado\UseCases\Gerenciamento\Pagamento\Requests\CriarFormaPagamentoRequest;
use Modules\Mercado\UseCases\Gerenciamento\Pagamento\Requests\EditarFormaPagamentoRequest;

class EditarFormaPagamento
{
    private EditarFormaPagamentoRequest $request;

    public function __construct(EditarFormaPagamentoRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
       return $this->editarFormaPagamento();
    }

    private function editarFormaPagamento(){
        return PagamentoRepository::update(
            $this->request->getId(),
            $this->request->getCriarFormaPagamentoRequest()->getDescricao(),
            $this->request->getCriarFormaPagamentoRequest()->getAtivo(),
            $this->request->getCriarFormaPagamentoRequest()->getParcelas(),
            $this->request->getCriarFormaPagamentoRequest()->getEspeciePagamentoId(),
            $this->request->getCriarFormaPagamentoRequest()->getLojaId(),
            $this->request->getCriarFormaPagamentoRequest()->getCriarHistoricoRequest(),
        );
    }
}
