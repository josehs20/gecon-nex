<?php

namespace Modules\Mercado\Application;

use Modules\Mercado\UseCases\Gerenciamento\ClassificacaoProduto\CriarClassificacaoProduto;
use Modules\Mercado\UseCases\Gerenciamento\ClassificacaoProduto\Requests\CriarClassificacaoProdutoRequest;
use Modules\Mercado\UseCases\Gerenciamento\ClassificacaoProduto\Requests\UpdateClassificacaoProdutoRequest;
use Modules\Mercado\UseCases\Gerenciamento\ClassificacaoProduto\UpdateClassificacaoProduto;

class ClassificacaoProdutoApplication
{
    public static function criarClassificacaoProduto(CriarClassificacaoProdutoRequest $request)
    {
        $interact = new CriarClassificacaoProduto($request);
        return $interact->handle();
    }

    public static function editarClassificacaoProduto(UpdateClassificacaoProdutoRequest $request)
    {
        $interact = new UpdateClassificacaoProduto($request);
        return $interact->handle();
    }
}
