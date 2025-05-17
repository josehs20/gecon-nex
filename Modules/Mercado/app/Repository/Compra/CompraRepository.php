<?php

namespace Modules\Mercado\Repository\Compra;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Modules\Mercado\Entities\Compra;
use Modules\Mercado\Entities\CompraItem;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class CompraRepository
{
    public static function create(
        $loja_id,
        $usuario_id,
        $cotacao_id,
        $cot_fornecedor_id,
        $status_id,
        $especie_pagamento_id,
        CriarHistoricoRequest $criarHistoricoRequest
    ) {
        Compra::setHistorico($criarHistoricoRequest);
        return Compra::create([
            'loja_id' => $loja_id,
            'usuario_id' => $usuario_id,
            'cotacao_id' => $cotacao_id,
            'cot_fornecedor_id' => $cot_fornecedor_id,
            'status_id' => $status_id,
            'especie_pagamento_id' => $especie_pagamento_id,
        ]);
    }

    public static function update(
        $id,
        $loja_id,
        $usuario_id,
        $cotacao_id,
        $cot_fornecedor_id,
        $status_id,
        $especie_pagamento_id,
        CriarHistoricoRequest $criarHistoricoRequest
    ) {
        $compra = Compra::find($id);
        Compra::setHistorico($criarHistoricoRequest);
        $compra->update([
            'loja_id' => $loja_id,
            'usuario_id' => $usuario_id,
            'cotacao_id' => $cotacao_id,
            'cot_fornecedor_id' => $cot_fornecedor_id,
            'status_id' => $status_id,
            'especie_pagamento_id' => $especie_pagamento_id,
        ]);
        return $compra;
    }

    public static function atualizaStatus(
        $id,
        $status_id,
        CriarHistoricoRequest $criarHistoricoRequest
    ) {
        $compra = Compra::find($id);
        Compra::setHistorico($criarHistoricoRequest);
        $compra->update([
            'status_id' => $status_id,
        ]);
        return $compra;
    }

    public static function getCompraById($compra_id) {
        return Compra::with(['cot_fornecedor.fornecedor', 'cotacao.status', 'status'])->find($compra_id);
    }
    /**
     * Parte dos itens
     *
     */

    public static function createCompraItem(
        $compra_id,
        $loja_id,
        $cot_for_item_id,
        CriarHistoricoRequest $criarHistoricoRequest
    ) {
        CompraItem::setHistorico($criarHistoricoRequest);
        return CompraItem::create([
            'compra_id' => $compra_id,
            'loja_id' => $loja_id,
            'cot_for_item_id' => $cot_for_item_id
        ]);
    }

    public static function getCompras(
        int $loja_id
    ){
        return Compra::where('loja_id', $loja_id)->get();
    }

    public static function getComprasCompradas(
        int $loja_id
    ){
        return Compra::where('loja_id', $loja_id)->where('status_id', config('config.status.comprado'))->get();
    }

    public static function getComprasCanceladas(
        int $loja_id
    ){
        return Compra::where('loja_id', $loja_id)->where('status_id', config('config.status.cancelado'))->get();
    }

    public static function getTotalEmComprasPorMes(
        int $loja_id
    ){
        return Compra::with('cot_fornecedor')
            ->where('loja_id', $loja_id)
            ->where('status_id', '!=', config('config.status.cancelado'))
            ->get()
            ->groupBy(function ($compra) {
                return Carbon::parse($compra->created_at)->format('Y-m');
            })
            ->map(function (Collection $comprasDoMes) {           
                return $comprasDoMes->sum(function ($compra) {
                    return optional($compra->cot_fornecedor)->total ?? 0;
                });
            })
            ->sortKeysDesc()
            ->take(12)
            ->reverse();  
    }
}
