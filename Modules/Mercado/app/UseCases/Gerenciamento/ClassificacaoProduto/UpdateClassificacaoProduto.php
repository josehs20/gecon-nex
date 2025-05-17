<?php

namespace Modules\Mercado\UseCases\Gerenciamento\ClassificacaoProduto;

use Exception;
use Modules\Mercado\Repository\ClassificacaoProduto\ClassificacaoProdutoRepository;
use Modules\Mercado\UseCases\Gerenciamento\ClassificacaoProduto\Requests\UpdateClassificacaoProdutoRequest;

class UpdateClassificacaoProduto
{
    private UpdateClassificacaoProdutoRequest $request;

    public function __construct(UpdateClassificacaoProdutoRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        self::validate();

        return self::updateUidadeMedida();
    }

    private function updateUidadeMedida()
    {
        return ClassificacaoProdutoRepository::update($this->request->getId(),$this->request->getNome(), $this->request->getEmpresaId(), $this->request->getCriarHistoricoRequest());
    }

    private function validate()
    {
        return true;
    }
}
