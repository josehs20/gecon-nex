<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Pagamento;

use Modules\Mercado\Repository\Pagamento\PagamentoRepository;
use Modules\Mercado\UseCases\Gerenciamento\Pagamento\Requests\CriarFormaPagamentoRequest;

class CriarFormaPagamento
{
    private CriarFormaPagamentoRequest $request;

    public function __construct(CriarFormaPagamentoRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        return $this->criarFormaPagamento();
    }

    private function criarFormaPagamento()
    {
        return PagamentoRepository::create(
            $this->request->getDescricao(),
            $this->request->getAtivo(),
            $this->request->getEspeciePagamentoId(),
            $this->request->getLojaId(),
            $this->request->getCriarHistoricoRequest()
        );
    }
}
