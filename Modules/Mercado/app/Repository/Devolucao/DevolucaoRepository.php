<?php

namespace Modules\Mercado\Repository\Devolucao;

use Modules\Mercado\Entities\Devolucao;
use Modules\Mercado\Entities\DevolucaoItem;
use Modules\Mercado\Entities\EspeciePagamento;
use Modules\Mercado\Entities\VendaPagamentoDevolucao;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class DevolucaoRepository
{
    public static function getAllTipoDevolucoes()
    {
        return EspeciePagamento::get();
    }

    // ------------------devolucao -----------------//

    public static function atualizaTotalDevolvido(
        int $devolucao_id,
        int $total_devolvido,
        CriarHistoricoRequest $criarHistoricoRequest
    ) {
        $devolucao = Devolucao::find($devolucao_id);
        Devolucao::setHistorico($criarHistoricoRequest);
        $devolucao->update([
            'total_devolvido' => $total_devolvido
        ]);

        return $devolucao;
    }

    public static function criar_devolucao(
        int $venda_id,
        int $caixa_id,
        int $caixa_evidencia_id,
        int $loja_id,
        int $usuario_id,
        mixed $data_devolucao,
        mixed $total_devolvido,
        string $motivo,
        CriarHistoricoRequest $criarHistoricoRequest
    ) {
        Devolucao::setHistorico($criarHistoricoRequest);
        return Devolucao::create([
            'venda_id' => $venda_id,
            'loja_id' => $loja_id,
            'usuario_id' => $usuario_id,
            'caixa_id' => $caixa_id,
            'caixa_evidencia_id' => $caixa_evidencia_id,
            'motivo' => $motivo,
            'data_devolucao' => $data_devolucao,
            'total_devolvido' => $total_devolvido
        ]);
    }

    public static function atualiza_devolucao(
        int $devolucao_id,
        int $venda_id,
        int $caixa_id,
        int $caixa_evidencia_id,
        int $loja_id,
        int $usuario_id,
        mixed $data_devolucao,
        mixed $total_devolvido,
        string $motivo,
        CriarHistoricoRequest $criarHistoricoRequest
    ) {
        $devolucao = Devolucao::find($devolucao_id);
        Devolucao::setHistorico($criarHistoricoRequest);

        $devolucao->update([
            'venda_id' => $venda_id,
            'caixa_id' => $caixa_id,
            'caixa_evidencia_id' => $caixa_evidencia_id,
            'loja_id' => $loja_id,
            'usuario_id' => $usuario_id,
            'motivo' => $motivo,
            'data_devolucao' => $data_devolucao,
            'total_devolvido' => $total_devolvido
        ]);

        return $devolucao;
    }
    public static function getDevolucaoById(
        int $devolucao_id,
    ) {
        return Devolucao::with(['devolucao_itens' => function($q){
            $q->with(['produto', 'venda_item']);
        }, 'venda_pagamentos_devolucao', 'venda'])->find($devolucao_id);
    }
    // ------------------Itens devolucao -----------------//
    public static function criar_devolucao_item(
        int $devolucao_id,
        int $loja_id,
        int $venda_id,
        int $caixa_id,
        int $caixa_evidencia_id,
        int $venda_item_id,
        int $estoque_origem_id,
        int $estoque_destino_id,
        int $produto_id,
        mixed $data_devolucao,
        float $quantidade,
        float $preco,
        float $total,
        CriarHistoricoRequest $criarHistoricoRequest
    ) {
        DevolucaoItem::setHistorico($criarHistoricoRequest);
        return DevolucaoItem::create([
            'devolucao_id' => $devolucao_id,
            'loja_id' => $loja_id,
            'venda_id' => $venda_id,
            'caixa_id' => $caixa_id,
            'caixa_evidencia_id' => $caixa_evidencia_id,
            'venda_item_id' => $venda_item_id,
            'estoque_origem_id' => $estoque_origem_id,
            'estoque_destino_id' => $estoque_destino_id,
            'produto_id' => $produto_id,
            'data_devolucao' => $data_devolucao,
            'quantidade' => $quantidade,
            'preco' => $preco,
            'total' => $total
        ]);
    }

    public static function atualiza_devolucao_item(
        int $devolucao_item_id,
        int $devolucao_id,
        int $loja_id,
        int $venda_id,
        int $caixa_id,
        int $caixa_evidencia_id,
        int $venda_item_id,
        int $estoque_origem_id,
        int $estoque_destino_id,
        int $produto_id,
        mixed $data_devolucao,
        float $quantidade,
        float $preco,
        float $total,
        CriarHistoricoRequest $criarHistoricoRequest
    ) {
        $devolucao_item =  DevolucaoItem::find($devolucao_item_id);
        DevolucaoItem::setHistorico($criarHistoricoRequest);
        $devolucao_item->update([
            'devolucao_id' => $devolucao_id,
            'loja_id' => $loja_id,
            'venda_id' => $venda_id,
            'caixa_id' => $caixa_id,
            'caixa_evidencia_id' => $caixa_evidencia_id,
            'venda_item_id' => $venda_item_id,
            'estoque_origem_id' => $estoque_origem_id,
            'estoque_destino_id' => $estoque_destino_id,
            'produto_id' => $produto_id,
            'data_devolucao' => $data_devolucao,
            'quantidade' => $quantidade,
            'preco' => $preco,
            'total' => $total
        ]);

        return $devolucao_item;
    }

    // public static function criar_devolucao_itens(
    //     array $arrayDeItemDevolucaos
    // ) {
    //     return DevolucaoItem::insert($arrayDeItemDevolucaos);
    // }

    //---------------------venda pagamento devolucao ---------------------//
    public static function criar_venda_pagamento_devolucao(
        $loja_id,
        $venda_id,
        $caixa_id,
        $caixa_evidencia_id,
        $devolucao_id,
        $venda_pagamento_id,
        $valor,
        CriarHistoricoRequest $criarHistoricoRequest
    ) {
        VendaPagamentoDevolucao::setHistorico($criarHistoricoRequest);
        return VendaPagamentoDevolucao::create([
            'loja_id' => $loja_id,
            'venda_id' => $venda_id,
            'caixa_id' => $caixa_id,
            'caixa_evidencia_id' => $caixa_evidencia_id,
            'devolucao_id' => $devolucao_id,
            'venda_pagamento_id' => $venda_pagamento_id,
            'valor' => $valor
        ]);
    }

    public static function atualiza_venda_pagamento_devolucao(
        $id,
        $loja_id,
        $caixa_id,
        $caixa_evidencia_id,
        $venda_id,
        $devolucao_id,
        $venda_pagamento_id,
        $valor,
        CriarHistoricoRequest $criarHistoricoRequest
    ) {
        $vendaPDevolucao = VendaPagamentoDevolucao::find($id);
        VendaPagamentoDevolucao::setHistorico($criarHistoricoRequest);
        $vendaPDevolucao->update([
            'loja_id' => $loja_id,
            'venda_id' => $venda_id,
            'caixa_id' => $caixa_id,
            'caixa_evidencia_id' => $caixa_evidencia_id,
            'devolucao_id' => $devolucao_id,
            'venda_pagamento_id' => $venda_pagamento_id,
            'valor' => $valor
        ]);
        return $vendaPDevolucao;
    }

    public static function getDevolucaoVendaByCaixa(
        $venda_id,
        $caixa_id,
        $caixa_evidencia_id
    ){
        return Devolucao::with('venda')->where('venda_id', $venda_id)->where('caixa_id', $caixa_id)->where('caixa_evidencia_id', $caixa_evidencia_id)->first();
    }
}
