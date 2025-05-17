<?php

namespace Modules\Mercado\Application;

use Modules\Mercado\Repository\Balanco\BalancoRepository;
use Modules\Mercado\UseCases\Gerenciamento\Balanco\CancelarBalanco;
use Modules\Mercado\UseCases\Gerenciamento\Balanco\ConfereQuantidadesBalancoItens;
use Modules\Mercado\UseCases\Gerenciamento\Balanco\CriarBalanco;
use Modules\Mercado\UseCases\Gerenciamento\Balanco\CriarBalancoItem;
use Modules\Mercado\UseCases\Gerenciamento\Balanco\DeletaBalancoItem;
use Modules\Mercado\UseCases\Gerenciamento\Balanco\FinalizarBalanco;
use Modules\Mercado\UseCases\Gerenciamento\Balanco\Requests\BalancoItemRequest;
use Modules\Mercado\UseCases\Gerenciamento\Balanco\Requests\BalancoRequest;
use Modules\Mercado\UseCases\Gerenciamento\Balanco\UpdateBalanco;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class BalancoApplication
{
    public static function getTodosBalancos(int $loja_id)
    {
        return BalancoRepository::getTodosBalancos($loja_id);
    }

    public static function getBalancoPorId(int $balanco_id)
    {
        return BalancoRepository::getBalancoPorId($balanco_id);
    }

    public static function getBalancoItensPorBalancoId(int $balanco_id)
    {
        return BalancoRepository::getBalancoItensPorBalancoId($balanco_id);
    }

    public static function verificarExistenciaBalancoEmAberto(int $loja_id)
    {
        return BalancoRepository::verificarExistenciaBalancoEmAberto($loja_id);
    }

    public static function createBalanco(BalancoRequest $request)
    {
        $interact = new CriarBalanco($request);
        return $interact->handle();
    }

    public static function updateBalanco($balanco_id, BalancoRequest $request)
    {
        $interact = new UpdateBalanco($balanco_id, $request);
        return $interact->handle();
    }

    public static function createBalancoItem(BalancoItemRequest $request)
    {
        $interact = new CriarBalancoItem($request);
        return $interact->handle();
    }

    public static function deleteBalancoItem(int $balanco_item_id, CriarHistoricoRequest $criarHistoricoRequest)
    {
        $interact = new DeletaBalancoItem($balanco_item_id, $criarHistoricoRequest);
        return $interact->handle();
    }

    public static function finalizarBalanco(ServiceUseCase $service, int $balanco_id, $observacao)
    {
        $interact = new FinalizarBalanco($service, $balanco_id, $observacao);
        return $interact->handle();
    }

    public static function confereQuantidadeEstoqueItens(int $balanco_id)
    {
        $interact = new ConfereQuantidadesBalancoItens($balanco_id);
        return $interact->handle();
    }

    public static function cancelarBalanco(int $balanco_id, CriarHistoricoRequest $criarHistoricoRequest)
    {
        $interact = new CancelarBalanco($balanco_id, $criarHistoricoRequest);
        return $interact->handle();
    }
}
