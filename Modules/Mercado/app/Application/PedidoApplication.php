<?php

namespace Modules\Mercado\Application;

use Modules\Mercado\UseCases\Pedido\Pedido\AtualizaPedido;
use Modules\Mercado\UseCases\Pedido\Pedido\AtualizaStatusPedido;
use Modules\Mercado\UseCases\Pedido\Pedido\CancelarPedido;
use Modules\Mercado\UseCases\Pedido\Pedido\CriarPedido;
use Modules\Mercado\UseCases\Pedido\Pedido\FinalizaPedido;
use Modules\Mercado\UseCases\Pedido\Pedido\Requests\CriarPedidoRequest;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\Pedido\Pedido\AtualizaStatusPedidoItem;

class PedidoApplication
{
    public static function criarPedido(CriarPedidoRequest $request)
    {
        $interact = new CriarPedido($request);
        return $interact->handle();
    }

    public static function atualizaPedido($pedido_id, CriarPedidoRequest $request)
    {
        $interact = new AtualizaPedido($pedido_id, $request);
        return $interact->handle();
    }

    public static function finalizarPedido($pedido_id, CriarHistoricoRequest $request)
    {
        $interact = new FinalizaPedido($pedido_id, $request);
        return $interact->handle();
    }

    public static function atualizaStatusPedido(CriarHistoricoRequest $criarHistoricoRequest, int $pedido_id, int $status_id)
    {
        $interact = new AtualizaStatusPedido($criarHistoricoRequest, $pedido_id, $status_id);
        return $interact->handle();
    }

    public static function atualizaStatusPedidoItem(CriarHistoricoRequest $criarHistoricoRequest, int $pedido_item_id, int $status_id)
    {
        $interact = new AtualizaStatusPedidoItem($criarHistoricoRequest, $pedido_item_id, $status_id);
        return $interact->handle();
    }

    public static function cancelarPedido(CriarHistoricoRequest $criarHistoricoRequest, int $pedido_id)
    {
        $interact = new CancelarPedido($pedido_id, $criarHistoricoRequest);
        return $interact->handle();
    }
}
