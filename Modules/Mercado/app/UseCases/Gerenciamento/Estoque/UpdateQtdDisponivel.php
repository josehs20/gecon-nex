<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Estoque;

use Modules\Mercado\Repository\Estoque\EstoqueRepository;
use Modules\Mercado\UseCases\Gerenciamento\Estoque\Requests\UpdateQtdDisponivelRequest;

class UpdateQtdDisponivel
{
    private UpdateQtdDisponivelRequest $request;
    public function __construct(UpdateQtdDisponivelRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        return $this->updateQtdDisponivel();
    }

    private function updateQtdDisponivel(){
        return EstoqueRepository::updateQtdDisponivel(
            $this->request->getId(),
            $this->request->getQuantidadeDisponivel(),
            $this->request->getQtdTotal(),
            $this->request->getHistoricoRequest()
        );
    }

}
