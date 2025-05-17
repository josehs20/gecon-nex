<?php

namespace Modules\Mercado\Repository\Cotacao;

use Modules\Mercado\Entities\CotacaoFornecedor;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class CotFornecedorRepository
{
    /**
     * Parte cotação fornecedores
     */

    public static function create(
        CriarHistoricoRequest $criarHistoricoRequest,
        $cotacao_id,
        $loja_id,
        $fornecedor_id,
        $desconto = null,
        $sub_total = null,
        $total = null,
        $frete = null,
        $observacao = null,
        $previsao_entrega = null
    ) {
        CotacaoFornecedor::setHistorico($criarHistoricoRequest);
        return CotacaoFornecedor::create([
            'cotacao_id' => $cotacao_id,
            'loja_id' => $loja_id,
            'fornecedor_id' => $fornecedor_id,
            'desconto' => $desconto,
            'total' => $total,
            'sub_total' => $sub_total,
            'frete' => $frete,
            'observacao' => $observacao,
            'previsao_entrega' => $previsao_entrega
        ]);
    }
    public static function update(
        $id,
        CriarHistoricoRequest $criarHistoricoRequest,
        $cotacao_id,
        $loja_id,
        $fornecedor_id,
        $desconto = null,
        $sub_total = null,
        $total = null,
        $frete = null,
        $observacao = null,
        $previsao_entrega = null
    ) {
        $cot_fornecedor = CotacaoFornecedor::find($id);
        CotacaoFornecedor::setHistorico($criarHistoricoRequest);
        $cot_fornecedor->update([
            'cotacao_id' => $cotacao_id,
            'loja_id' => $loja_id,
            'fornecedor_id' => $fornecedor_id,
            'desconto' => $desconto,
            'total' => $total,
            'sub_total' => $sub_total,
            'frete' => $frete,
            'observacao' => $observacao,
            'previsao_entrega' => $previsao_entrega
        ]);
        return $cot_fornecedor;
    }
    public static function getCotFornecedorById(
        int $id
    ) {
        return CotacaoFornecedor::with(['cotacao', 'cot_for_itens', 'fornecedor'])->find($id);
    }
}
