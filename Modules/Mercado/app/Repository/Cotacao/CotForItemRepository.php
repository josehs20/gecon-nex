<?php

namespace Modules\Mercado\Repository\Cotacao;

use Modules\Mercado\Entities\CotacaoFornecedorItem;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class CotForItemRepository
{
    /**
     * Parte cotação fornecedores itens
     */

     public static function create(
        CriarHistoricoRequest $criarHistoricoRequest,
        $cotacao_fornecedor_id,
        $fornecedor_id,
        $pedido_id,
        $cotacao_id,
        $pedido_item_id,
        $loja_id,
        $estoque_id,
        $produto_id,
        $status_id,
        $quantidade,
        $preco_unitario = null
    ) {
        CotacaoFornecedorItem::setHistorico($criarHistoricoRequest);
        return CotacaoFornecedorItem::create([
            'cotacao_fornecedor_id' => $cotacao_fornecedor_id,
            'fornecedor_id' => $fornecedor_id,
            'pedido_id' => $pedido_id,
            'cotacao_id' => $cotacao_id,
            'pedido_item_id' => $pedido_item_id,
            'loja_id' => $loja_id,
            'estoque_id' => $estoque_id,
            'produto_id' => $produto_id,
            'status_id' => $status_id,
            'quantidade' => $quantidade,
            'preco_unitario' => $preco_unitario
        ]);
    }

    public static function atualizaStatus(
        CriarHistoricoRequest $criarHistoricoRequest,
        $id,
        $status_id
    ) {
        $cotForItem = CotacaoFornecedorItem::find($id);
        CotacaoFornecedorItem::setHistorico($criarHistoricoRequest);
        $cotForItem->update([
            'status_id' => $status_id
        ]);
        return $cotForItem;
    }

    public static function updatePrecoUnitario(
        CriarHistoricoRequest $criarHistoricoRequest,
        $id,
        $preco_unitario,
        $status_id = null
    ) {
        $cotForItem = CotacaoFornecedorItem::find($id);

        CotacaoFornecedorItem::setHistorico($criarHistoricoRequest);

        $dadosUpdate = [
            'preco_unitario' => $preco_unitario
        ];

        if ($status_id) {
            $dadosUpdate['status_id'] = $status_id;
        }

        $cotForItem->update($dadosUpdate);

        return $cotForItem;
    }
}
