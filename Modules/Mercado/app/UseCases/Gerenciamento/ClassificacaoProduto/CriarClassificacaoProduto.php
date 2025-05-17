<?php

namespace Modules\Mercado\UseCases\Gerenciamento\ClassificacaoProduto;

use Exception;
use Modules\Mercado\Repository\ClassificacaoProduto\ClassificacaoProdutoRepository;
use Modules\Mercado\UseCases\Gerenciamento\ClassificacaoProduto\Requests\CriarClassificacaoProdutoRequest;

class CriarClassificacaoProduto
{
    private CriarClassificacaoProdutoRequest $request;

    public function __construct(CriarClassificacaoProdutoRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        self::validate();

        return self::criarClassificacaoProduto();
    }

    private function criarClassificacaoProduto()
    {
        return ClassificacaoProdutoRepository::create($this->request->getNome(), $this->request->getEmpresaId(), $this->request->getCriarHistoricoRequest());
    }

    private function validate()
    {
        $existe = ClassificacaoProdutoRepository::getCpByDescricao($this->request->getNome());

        if ($existe) {
            throw new Exception("Classificacao de produto já existe!", 1);
        }
    }
}
