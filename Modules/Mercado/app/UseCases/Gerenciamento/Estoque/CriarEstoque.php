<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Estoque;

use Modules\Mercado\Repository\Estoque\EstoqueRepository;
use Modules\Mercado\UseCases\Gerenciamento\Estoque\Requests\CriarEstoqueRequest;

class CriarEstoque
{
    private CriarEstoqueRequest $request;

    public function __construct(CriarEstoqueRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $estoque = $this->criarEstoque();

        return $estoque;
    }

    public function criarEstoque()
    {
        return EstoqueRepository::create(
            $this->request->getCusto(),
            $this->request->getPreco(),
            $this->request->getProdutoId(),
            $this->request->getLojaId(),
            $this->request->getQuantidadeTotal(),
            $this->request->getQuantidadeDisponivel(),
            $this->request->getQuantidadeMinima(),
            $this->request->getQuantidadeMaxima(),
            $this->request->getLocalizacao(),
            $this->request->getHistoricoRequest()
        );
    }
}
