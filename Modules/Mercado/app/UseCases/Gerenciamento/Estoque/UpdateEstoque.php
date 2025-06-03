<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Estoque;

use Modules\Mercado\Repository\Estoque\EstoqueRepository;
use Modules\Mercado\UseCases\Gerenciamento\Estoque\Requests\UpdateEstoqueRequest;

class UpdateEstoque
{
    private UpdateEstoqueRequest $request;

    public function __construct(UpdateEstoqueRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $estoque = $this->update();
        return $estoque;
    }

    public function update()
    {
        return EstoqueRepository::update(
            $this->request->getId(),
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
