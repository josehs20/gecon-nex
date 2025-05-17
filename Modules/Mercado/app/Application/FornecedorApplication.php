<?php

namespace Modules\Mercado\Application;

use Modules\Mercado\UseCases\Gerenciamento\Fornecedor\AtualizarFornecedor;
use Modules\Mercado\UseCases\Gerenciamento\Fornecedor\CriarFornecedor;
use Modules\Mercado\UseCases\Gerenciamento\Fornecedor\GetFornecedorById;
use Modules\Mercado\UseCases\Gerenciamento\Fornecedor\GetTodosFornecedoresPorAtivo;
use Modules\Mercado\UseCases\Gerenciamento\Fornecedor\Requests\FornecedorRequest;

class FornecedorApplication
{

    public static function criarFornecedor(FornecedorRequest $request){
        $interact = new CriarFornecedor($request);
        return $interact->handle();
    }

    public static function atualizarFornecedor(FornecedorRequest $request, $id){
        $interact = new AtualizarFornecedor($request, $id);
        return $interact->handle();
    }

    public static function getTodosFornecedores(bool $ativo){
        $interact = new GetTodosFornecedoresPorAtivo($ativo);
        return $interact->handle();
    }

    public static function getFornecedorById(int $id){
        $interact= new GetFornecedorById($id);
        return $interact->handle();
    }
}
