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
        return EstoqueRepository::updateQtdEstoque(
            $this->request->getId(),
            $this->request->getQuantidadeTotal(),
            $this->request->getQuantidadeDisponivel(),
            $this->request->getQuantidadeMinima(),
            $this->request->getQuantidadeMinima(),
            $this->request->getLocalizacao(),
            $this->request->getHistoricoRequest()
        );
    }
}
