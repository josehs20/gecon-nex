<?php

namespace Modules\Mercado\Repository\Venda;

use Modules\Mercado\Entities\Venda;
use Modules\Mercado\Entities\VendaItem;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class VendaRepository
{
    public static array $select = [
        't1.*',
        't2.nome as caixa_nome',
        't3.nome as cliente_nome',
    ];

    public static function create(
        int $caixa_id,
        int $caixa_evidencia_id,
        int $loja_id,
        int $usuario_id,
        int $status_id,
        int $sub_total,
        int $total,
        ?string $n_venda = null,
        ?float $desconto_porcentagem = null,
        ?int $desconto_dinheiro = null,
        ?int $cliente_id = null,
        mixed $data_concluida,
        CriarHistoricoRequest $historicoRequest
    ): ?Venda {
        Venda::setHistorico($historicoRequest);
        return Venda::create([
            'n_venda' => $n_venda,
            'cliente_id' => $cliente_id,
            'caixa_id' => $caixa_id,
            'caixa_evidencia_id' => $caixa_evidencia_id,
            'loja_id' => $loja_id,
            'usuario_id' => $usuario_id,
            'status_id' => $status_id,
            'sub_total' => $sub_total,
            'total' => $total,
            'desconto_porcentagem' => $desconto_porcentagem,
            'desconto_dinheiro' => $desconto_dinheiro,
            'data_concluida' => $data_concluida
        ]);
    }

    public static function update(
        int $id,
        int $caixa_id,
        int $caixa_evidencia_id,
        int $loja_id,
        int $usuario_id,
        int $status_id,
        int $sub_total,
        int $total,
        ?float $desconto_porcentagem = null,
        ?int $desconto_dinheiro = null,
        ?int $cliente_id = null,
        mixed $data_concluida,
        CriarHistoricoRequest $historicoRequest
    ): ?Venda {
        $venda = Venda::find($id);
        Venda::setHistorico($historicoRequest);

        $venda->update([
            'cliente_id' => $cliente_id  ? $cliente_id : $venda->cliente_id,
            'loja_id' => $loja_id,
            'caixa_id' => $caixa_id,
            'caixa_evidencia_id' => $caixa_evidencia_id,
            'usuario_id' => $usuario_id,
            'status_id' => $status_id,
            'sub_total' => $sub_total,
            'total' => $total,
            'desconto_porcentagem' => $desconto_porcentagem,
            'desconto_dinheiro' => $desconto_dinheiro,
            'data_concluida' => $data_concluida
        ]);
        // $venda->setAudit($historicoRequest);
        return $venda;
    }

    public static function verificaSeNumeroVendaExiste(int $loja_id, string $numeroVenda)
    {
        return Venda::where('n_venda', $numeroVenda)->where('loja_id', $loja_id)->exists();
    }

    public static function deleteVenda(int $venda_id, CriarHistoricoRequest $criarHistoricoRequest)
    {
        $venda = Venda::where('id', $venda_id)->first();
        Venda::setHistorico($criarHistoricoRequest);
        $venda->update(['status_id'=> config('config.status.cancelado')]);
        return $venda->delete();
    }

    public static function getVendaByNVenda(int $loja_id, string $numeroVenda)
    {
        return Venda::where('n_venda', $numeroVenda)->where('loja_id', $loja_id)->first();
    }

    public static function getVendaById(int $id)
    {
        return Venda::with(['venda_itens' => function ($q) {
            $q->with(['estoque' => function ($q) {
                $q->with(['produto' => function ($q) {
                    $q->with('unidade_medida');
                }]);
            }, 'devolucao_item']);
        }, 'caixa','devolucoes','venda_pagamentos' => function($q){
            $q->with(['forma', 'especie', 'venda_pagamento_devolucao']);
        }, 'cliente', 'usuario' => function($q){
            $q->with('master');
        }])->find($id);
    }

    public static function getVendaItemByIds(int $loja_id, array $ids)
    {
        return VendaItem::with(['estoque', 'venda'])->where('loja_id', $loja_id)->whereIn('id', $ids)->get();
    }
    public static function atualizaStatusVenda(
        $venda_id,
        $status_id,
        CriarHistoricoRequest $historicoRequest
    ) {
        $venda = Venda::find($venda_id);
        Venda::setHistorico($historicoRequest);
        $venda->update([
            'status_id' => $status_id,
        ]);

        // $venda->setAudit($historicoRequest);
        return $venda;
    }
}
