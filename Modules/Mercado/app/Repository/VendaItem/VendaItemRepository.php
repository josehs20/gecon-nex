<?php

namespace Modules\Mercado\Repository\VendaItem;

use Modules\Mercado\Entities\VendaItem;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class VendaItemRepository
{
    public static function create(
        CriarHistoricoRequest $criarHistoricoRequest,
        int $venda_id,
        int $caixa_id,
        int $caixa_evidencia_id,
        int $loja_id,
        int $estoque_id,
        int $produto_id,
        float $quantidade,
        float $preco,
        float $total
    ) {
        VendaItem::setHistorico($criarHistoricoRequest);
        return VendaItem::create([
            'venda_id' => $venda_id,
            'loja_id' => $loja_id,
            'caixa_id' => $caixa_id,
            'caixa_evidencia_id' => $caixa_evidencia_id,
            'estoque_id' => $estoque_id,
            'produto_id' => $produto_id,
            'quantidade' => $quantidade,
            'preco' => $preco,
            'total' => $total
        ]);
    }

    public static function update(
        CriarHistoricoRequest $criarHistoricoRequest,
        int $id,
        int $venda_id,
        int $caixa_id,
        int $caixa_evidencia_id,
        int $loja_id,
        int $estoque_id,
        int $produto_id,
        float $quantidade,
        float $preco,
        float $total
    ) {
        $item = VendaItem::find($id);
        VendaItem::setHistorico($criarHistoricoRequest);
        $item->update([
            'venda_id' => $venda_id,
            'loja_id' => $loja_id,
            'caixa_id' => $caixa_id,
            'caixa_evidencia_id' => $caixa_evidencia_id,
            'estoque_id' => $estoque_id,
            'produto_id' => $produto_id,
            'quantidade' => $quantidade,
            'preco' => $preco,
            'total' => $total
        ]);
        return $item;
    }

    public static function createItens(
        array $itens
    ) {
        return VendaItem::insert($itens);
    }

    public static function getItemById(
        int $id
    ) {
        return VendaItem::find($id);
    }

    public static function removeItemDiferente(
        array $ids,
        int $venda_id,
        CriarHistoricoRequest $criarHistoricoRequest
    ) {
        VendaItem::setHistorico($criarHistoricoRequest);
        return VendaItem::where('venda_id', $venda_id)->whereNotIn('id', $ids)->get()
            ->each(function ($item) {
                $item->delete(); // Aciona o evento 'deleted'
            });
    }
}
