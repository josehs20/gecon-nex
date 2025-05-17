<?php

namespace Modules\Mercado\Repository\Pedido;

use Modules\Mercado\Entities\Pedido;
use Modules\Mercado\Entities\PedidoItem;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class PedidoRepository
{
    /**
     * Pedido
     */
    public static function create(
        CriarHistoricoRequest $historico,
        $loja_id,
        $status_id,
        $usuario_id,
        $data_limite,
        $observacao = null
    ) {
        Pedido::setHistorico($historico);
        return Pedido::create([
            'loja_id' => $loja_id,
            'status_id' => $status_id,
            'usuario_id' => $usuario_id,
            'data_limite' => $data_limite,
            'observacao' => $observacao,
        ]);
    }
    public static function atualiza(
        $id,
        CriarHistoricoRequest $historico,
        $loja_id,
        $status_id,
        $usuario_id,
        $data_limite,
        $observacao = null
    ) {
        $pedido = Pedido::find($id);
        Pedido::setHistorico($historico);
        $pedido->update([
            'loja_id' => $loja_id,
            'status_id' => $status_id,
            'usuario_id' => $usuario_id,
            'data_limite' => $data_limite,
            'observacao' => $observacao,
        ]);
        return $pedido;
    }
    public static function updateStatus(
        CriarHistoricoRequest $historico,
        $pedido_id,
        $status_id
    ) {
        Pedido::setHistorico($historico);
        $pedido = Pedido::find($pedido_id);
        $pedido->update([
            'status_id' => $status_id
        ]);
        return $pedido;
    }

    public static function getPedidos(int $loja_id)
    {
        return Pedido::with(['pedido_itens' => function ($q) {
            $q->with('produto');
        }, 'status', 'usuario' => function ($q) {
            $q->with('master');
        }])->where('loja_id', $loja_id)->get();
    }

    public static function getPedidosById(array $ids)
    {
        return Pedido::with(['status', 'pedido_itens' => function($q){
            $q->with(['produto.unidade_medida', 'produto.fabricante', 'status']);
        }])->whereIn('id', $ids)->get();
    }

    public static function getPedidosPorStatus(int $loja_id, array $status_id)
    {
        return Pedido::with(['pedido_itens' => function ($q) {
            $q->with(['produto.unidade_medida', 'produto.fabricante', 'status']);
        }, 'status', 'usuario' => function ($q) {
            $q->with('master');
        }])->where('loja_id', $loja_id)->whereIn('status_id', $status_id)->get();
    }

    public static function getPedidoById(int $id)
    {
        return Pedido::with(['pedido_itens.estoque.produto.unidade_medida', 'pedido_itens.status','pedido_itens.estoque.produto.fabricante', 'status', 'usuario' => function ($q) {
            $q->with('master');
        }])->find($id);
    }



    /**
     * Pedido item
     */

    public static function criaPedidoItem(
        CriarHistoricoRequest $historico,
        $pedido_id,
        $produto_id,
        $estoque_id,
        $loja_id,
        $quantidade_pedida,
        $status_id
    ) {
        PedidoItem::setHistorico($historico);
        // Verifica se já existe, mesmo que deletado
        $item = PedidoItem::withTrashed()
            ->where('pedido_id', $pedido_id)
            ->where('estoque_id', $estoque_id)
            ->first();
        if ($item) {
            // Se estava deletado, restaura
            if ($item->trashed()) {
                $item->restore();
            }
            $item->update([
                'pedido_id' => $pedido_id,
                'produto_id' => $produto_id,
                'estoque_id' => $estoque_id,
                'loja_id' => $loja_id,
                'status_id' => $status_id,
                'quantidade_pedida' => $quantidade_pedida,
            ]);
            return $item;
        }
        return PedidoItem::create([
            'pedido_id' => $pedido_id,
            'produto_id' => $produto_id,
            'estoque_id' => $estoque_id,
            'loja_id' => $loja_id,
            'status_id' => $status_id,
            'quantidade_pedida' => $quantidade_pedida,
        ]);
    }

    public static function atualizaPedidoItem(
        $id,
        CriarHistoricoRequest $historico,
        $pedido_id,
        $produto_id,
        $estoque_id,
        $loja_id,
        $quantidade_pedida,
        $status_id
    ) {
        $pedidoItem = PedidoItem::find($id);
        PedidoItem::setHistorico($historico);
        $pedidoItem->update([
            'pedido_id' => $pedido_id,
            'produto_id' => $produto_id,
            'estoque_id' => $estoque_id,
            'loja_id' => $loja_id,
            'status_id' => $status_id,
            'quantidade_pedida' => $quantidade_pedida,
        ]);
        return $pedidoItem;
    }

    public static function getItensDoPedido(int $pedidoId)
    {
        return PedidoItem::where('pedido_id', $pedidoId)
            ->get();
    }

    public static function removeItemDoPedido(int $pedidoItemId, CriarHistoricoRequest $criarHistoricoRequest)
    {
        PedidoItem::setHistorico($criarHistoricoRequest);
        PedidoItem::find($pedidoItemId)->delete();
        return true;
    }

    public static function getPedidoItemPorPedidoEEstoque($pedido_id, $estoque_id)
    {

        return PedidoItem::where('pedido_id', $pedido_id)->where('estoque_id', $estoque_id)->first();
    }

    public static function atualizaStatusPedidoItem(
        $id,
        $status_id,
        CriarHistoricoRequest $historico
    ) {
        $pedidoItem = PedidoItem::find($id);
        PedidoItem::setHistorico($historico);
        $pedidoItem->update([
            'status_id' => $status_id,
        ]);
        return $pedidoItem;
    }

    public static function getPedidosAguardandoCotacao(
        int $loja_id
    ){
        return Pedido::where('loja_id', $loja_id)->where('status_id', config('config.status.aguardando_cotacao'))->get();
    }

    public static function getPedidosCotados(
        int $loja_id
    ){
        return Pedido::where('loja_id', $loja_id)->where('status_id', config('config.status.cotado'))->get();
    }

    public static function getPedidosEmAberto(
        int $loja_id
    ){
        return Pedido::where('loja_id', $loja_id)->where('status_id', config('config.status.aberto'))->get();
    }

    public static function getPedidosEmCotacao(
        int $loja_id
    ){
        return Pedido::where('loja_id', $loja_id)->where('status_id', config('config.status.em_cotacao'))->get();
    }

    public static function getPedidosCancelados(
        int $loja_id
    ){
        return Pedido::where('loja_id', $loja_id)->where('status_id', config('config.status.cancelado'))->get();
    }

    public static function getPedidosComprados(
        int $loja_id
    ){
        return Pedido::where('loja_id', $loja_id)->where('status_id', config('config.status.comprado'))->get();
    }

    public static function getPedidosSemCotacao(
        int $loja_id
    ){
        return Pedido::whereHas('pedido_itens', function($q){
            $q->whereDoesntHave('cotacao_fornecedor_item');
        })
        ->where('loja_id', $loja_id)
        ->get();
    }

    public static function getPedidosComCotacao(
        int $loja_id
    ){
        return Pedido::whereHas('pedido_itens', function($q){
            $q->whereHas('cotacao_fornecedor_item');
        })
        ->where('loja_id', $loja_id)
        ->get();
    }
}
