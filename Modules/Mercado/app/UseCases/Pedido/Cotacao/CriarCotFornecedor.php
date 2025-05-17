<?php

namespace Modules\Mercado\UseCases\Pedido\Cotacao;

use Modules\Mercado\Repository\Cotacao\CotFornecedorRepository;
use Modules\Mercado\UseCases\Pedido\Cotacao\Requests\CriarCotFornecedorRequest;

class CriarCotFornecedor
{
    private CriarCotFornecedorRequest $request;

    public function __construct(CriarCotFornecedorRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        return $this->criar();
    }

    // Validação dos dados
    private function validade()
    {

    }

    private function criar()
    {
        return CotFornecedorRepository::create(
            $this->request->getCriarHistoricoRequest(),
            $this->request->getCotacaoId(),
            $this->request->getLojaId(),
            $this->request->getFornecedorId(),
            $this->request->getDesconto(),
            $this->request->getTotal(),
            $this->request->getFrete(),
            $this->request->getObservacao(),
            $this->request->getPrevisaoEntrega()
        );
    }

}
