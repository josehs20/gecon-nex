<?php

namespace Modules\Mercado\UseCases\Pedido\Cotacao;

use Modules\Mercado\Repository\Cotacao\CotFornecedorRepository;
use Modules\Mercado\UseCases\Pedido\Cotacao\Requests\CriarCotFornecedorRequest;

class AtualizarCotFornecedor
{
    private int $id;
    private CriarCotFornecedorRequest $request;

    public function __construct(int $id, CriarCotFornecedorRequest $request)
    {
        $this->request = $request;
        $this->id = $id;
    }

    public function handle()
    {
        return $this->atualizar();
    }

    // Validação dos dados
    private function validade()
    {

    }

    private function atualizar()
    {
        return CotFornecedorRepository::update(
            $this->id,
            $this->request->getCriarHistoricoRequest(),
            $this->request->getCotacaoId(),
            $this->request->getLojaId(),
            $this->request->getFornecedorId(),
            $this->request->getDesconto(),
            $this->request->getSubTotal(),
            $this->request->getTotal(),
            $this->request->getFrete(),
            $this->request->getObservacao(),
            $this->request->getPrevisaoEntrega()
        );
    }

}
