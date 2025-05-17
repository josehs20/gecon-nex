<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Estoque;

use Modules\Mercado\Repository\Estoque\EstoqueRepository;
use Modules\Mercado\UseCases\Gerenciamento\Estoque\Requests\UpdateQtdMinMaxRequest;

class UpdateQtdMinMax
{
    private UpdateQtdMinMaxRequest $request;

    public function __construct(UpdateQtdMinMaxRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $this->validate();
        $estoque = $this->update();

        return $estoque;
    }

    public function validate()
    {
        if ($this->request->getQuantidadeMaxima() < $this->request->getQuantidadeMinima()) {
            throw new \Exception("Quantidade mínima menor que máxima", 1);
        }
    }

    public function update()
    {
        return EstoqueRepository::updateQtdMinMax(
            $this->request->getId(),
            $this->request->getQuantidadeMaxima(),
            $this->request->getQuantidadeMinima(),
            $this->request->getLocalizacao(),
            $this->request->getCriarHistoricoRequest()
        );
    }
}
