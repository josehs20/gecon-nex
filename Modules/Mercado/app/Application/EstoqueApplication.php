<?php

namespace Modules\Mercado\Application;

use Modules\Mercado\Repository\Estoque\EstoqueRepository;
use Modules\Mercado\UseCases\Gerenciamento\Estoque\AtualizaNCM;
use Modules\Mercado\UseCases\Gerenciamento\Estoque\CriarEstoque;
use Modules\Mercado\UseCases\Gerenciamento\Estoque\Requests\AtualizaNCMRequest;
use Modules\Mercado\UseCases\Gerenciamento\Estoque\Requests\CriarEstoqueRequest;
use Modules\Mercado\UseCases\Gerenciamento\Estoque\Requests\UpdateEstoqueRequest;
use Modules\Mercado\UseCases\Gerenciamento\Estoque\Requests\UpdateQtdDisponivelRequest;
use Modules\Mercado\UseCases\Gerenciamento\Estoque\Requests\UpdateQtdMinMaxRequest;
use Modules\Mercado\UseCases\Gerenciamento\Estoque\UpdateEstoque;
use Modules\Mercado\UseCases\Gerenciamento\Estoque\UpdateQtdDisponivel;
use Modules\Mercado\UseCases\Gerenciamento\Estoque\UpdateQtdMinMax;

class EstoqueApplication
{
    public static function getEstoqueById(int $estoque_id){
        return EstoqueRepository::getEstoqueById($estoque_id);
    }

    public static function criarEstoque(CriarEstoqueRequest $request){
        $interact = new CriarEstoque($request);
        return $interact->handle();
    }

    public static function updateEstoque(UpdateEstoqueRequest $request){
        $interact = new UpdateEstoque($request);
        return $interact->handle();
    }

    public static function updateQtdMinMax(UpdateQtdMinMaxRequest $request){
        $interact = new UpdateQtdMinMax($request);
        return $interact->handle();
    }

    public static function updateQtdDisponivel(UpdateQtdDisponivelRequest $request){
        $interact = new UpdateQtdDisponivel($request);
        return $interact->handle();
    }

    public static function atualizaNCM(AtualizaNCMRequest $request){
        $interact = new AtualizaNCM($request);
        return $interact->handle();
    }

}
