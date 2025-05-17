<?php

namespace Modules\Mercado\Repository\Recebimento;

use Modules\Mercado\Entities\Recebimento;
use Modules\Mercado\Entities\RecebimentoItem;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class RecebimentoRepository
{
    public static function create(
        CriarHistoricoRequest $historico,
        $pedido_id,
        $usuario_id,
        $loja_id,
        $status_id,
        $data_recebimento,
        $arquivo = null,
        $observacoes = null
    ) {
        Recebimento::setHistorico($historico);
        return Recebimento::create([
            'pedido_id' => $pedido_id,
            'usuario_id' => $usuario_id,
            'loja_id' => $loja_id,
            'status_id' => $status_id,
            'data_recebimento' => $data_recebimento,
            'arquivo_id' => $arquivo,
            'observacoes' => $observacoes
        ]);
    }

    public static function update(
        CriarHistoricoRequest $historico,
        $id,
        $pedido_id,
        $usuario_id,
        $loja_id,
        $status_id,
        $data_recebimento,
        $arquio_id = null,
        $observacoes = null
    ) {
        Recebimento::setHistorico($historico);
        $recebimento = Recebimento::find($id);
        $recebimento->update([
            'pedido_id' => $pedido_id,
            'usuario_id' => $usuario_id,
            'loja_id' => $loja_id,
            'status_id' => $status_id,
            'data_recebimento' => $data_recebimento,
            'arquivo_id' => $arquio_id,
            'observacoes' => $observacoes
        ]);

        return $recebimento;
    }

    public static function atualizaStatusRecebimento(
        CriarHistoricoRequest $historico,
        $id,
        $status_id,
   
    ) {
        Recebimento::setHistorico($historico);
        $recebimento = Recebimento::find($id);
        $recebimento->update([
            'status_id' => $status_id,
        ]);
        return $recebimento;
    }
    /**
     * Recebimento item
     *  */
    public static function getRecebimentoItemByPedidoItemID(
        int $pedido_item_id
    ) {
        return RecebimentoItem::where('pedido_item_id', $pedido_item_id)->first();
    }
    public static function createRecebimentoItem(
        CriarHistoricoRequest $criarHistoricoRequest,
        $recebimento_id,
        $loja_id,
        $produto_id,
        $estoque_id,
        $pedido_item_id,
        $status_id,
        $quantidade_recebida,
        $quantidade_pedida,
        $preco_unitario,
        $total,
        $lote = null,
        $validade = null
    ) {
        RecebimentoItem::setHistorico($criarHistoricoRequest);
        return RecebimentoItem::create([
            'recebimento_id' => $recebimento_id,
            'loja_id' => $loja_id,
            'produto_id' => $produto_id,
            'estoque_id' => $estoque_id,
            'pedido_item_id' => $pedido_item_id,
            'status_id' => $status_id,
            'quantidade_recebida' => $quantidade_recebida,
            'quantidade_pedida' => $quantidade_pedida,
            'preco_unitario' => $preco_unitario,
            'total' => $total,
            'lote' => $lote,
            'validade' => $validade,
        ]);
    }

    public static function updateRecebimentoItem(
        CriarHistoricoRequest $criarHistoricoRequest,
        $recebimento_item_id,
        $recebimento_id,
        $loja_id,
        $produto_id,
        $estoque_id,
        $pedido_item_id,
        $status_id,
        $quantidade_recebida,
        $quantidade_pedida,
        $preco_unitario,
        $total,
        $lote = null,
        $validade = null
    ) {
        $recebimentoItem = RecebimentoItem::find($recebimento_item_id);
        RecebimentoItem::setHistorico($criarHistoricoRequest);
        $recebimentoItem->update([
            'recebimento_id' => $recebimento_id,
            'loja_id' => $loja_id,
            'produto_id' => $produto_id,
            'estoque_id' => $estoque_id,
            'pedido_item_id' => $pedido_item_id,
            'status_id' => $status_id,
            'quantidade_recebida' => $quantidade_recebida,
            'quantidade_pedida' => $quantidade_pedida,
            'preco_unitario' => $preco_unitario,
            'total' => $total,
            'lote' => $lote,
            'validade' => $validade,
        ]);
        return $recebimentoItem;
    }
}
