<?php

namespace Modules\Mercado\Application;

use Modules\Mercado\UseCases\Gerenciamento\Produto\CriarOrAtualizarProdutoPorNF;
use Modules\Mercado\UseCases\Gerenciamento\Produto\CriarProduto;
use Modules\Mercado\UseCases\Gerenciamento\Produto\EditarProduto;
use Modules\Mercado\UseCases\Gerenciamento\Produto\GerarCodAux;
use Modules\Mercado\UseCases\Gerenciamento\Produto\Requests\CriarOrAtualizarProdutoPorNFRequest;
use Modules\Mercado\UseCases\Gerenciamento\Produto\Requests\CriarProdutoRequest;
use Modules\Mercado\UseCases\Gerenciamento\Produto\Requests\EditarProdutoRequest;

class ProdutoApplication
{
    public static function criarProduto(CriarProdutoRequest $request)
    {
        $interact = new CriarProduto($request);
        return $interact->handle();
    }

    public static function editarProduto(EditarProdutoRequest $request)
    {
        $interact = new EditarProduto($request);
        return $interact->handle();
    }

    public static function gerarCodAux(int $loja_id)
    {
        $interact = new GerarCodAux($loja_id);
        return $interact->handle();
    }

    public static function criarOrAtualizaProdutoPorNF(CriarOrAtualizarProdutoPorNFRequest $request)
    {
        $interact = new CriarOrAtualizarProdutoPorNF($request);
        return $interact->handle();
    }
}
