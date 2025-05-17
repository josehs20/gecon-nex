<?php

namespace Modules\Mercado\Repository\MovimentacaoEstoque;

use Modules\Mercado\Entities\MovimentacaoEstoque;
use Modules\Mercado\Entities\MovimentacaoEstoqueItem;
use Modules\Mercado\Entities\TipoMovimentacaoEstoque;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class MovimentacaoEstoqueRepository
{
    public static function getTodasMovimentacoes(int $lojaId)
    {
        return MovimentacaoEstoque::select('movimentacao_estoque.*')
            ->distinct()
            ->where('movimentacao_estoque.loja_id', $lojaId)
            ->leftJoin('movimentacao_estoque_item', 'movimentacao_estoque.id', '=', 'movimentacao_estoque_item.movimentacao_id')
            ->where(function ($query) {
                $query->whereNotIn('movimentacao_estoque_item.tipo_movimentacao_estoque_id', [config('config.tipo_movimentacao_estoque.balanço')])
                    ->orWhereNull('movimentacao_estoque_item.tipo_movimentacao_estoque_id');
            })
            ->get();
    }

    public static function getMovimentacaoEstoquePorId(int $movimentacaoId)
    {
        return MovimentacaoEstoque::with(['movimentacao_estoque_itens.estoque.produto.unidade_medida', 'movimentacao_estoque_itens.estoque.produto.fabricante'])->find($movimentacaoId);
    }

    public static function getMovimentacoesItemPorMovimentacaoId(int $movimentacao_id)
    {
        return MovimentacaoEstoqueItem::with(['estoque' => function ($q) {
            $q->with(['produto' => function ($q) {
                $q->with(['unidade_medida', 'fabricante']);
            }]);
        }, 'tipo_movimentacao'])->where('movimentacao_id', $movimentacao_id)->get();
    }

    public static function atualizarStatusMovimentacaoEstoque(
        int $movimentacao_id,
        int $status_id,
        CriarHistoricoRequest $criarHistoricoRequest
    ) {
        $movimentacao = MovimentacaoEstoque::find($movimentacao_id);
        MovimentacaoEstoque::setHistorico($criarHistoricoRequest);
        $movimentacao->update([
            'status_id' => $status_id
        ]);
        // $movimentacao->setAudit($criarHistoricoRequest);
        return $movimentacao;
    }

    public static function createMovimentacaoEstoque(
        $lojaId,
        $statusId,
        $usuarioId,
        $tipo_movimentacao_estoque_id,
        CriarHistoricoRequest $criarHistoricoRequest,
        $observacao = null
    ) {
        MovimentacaoEstoque::setHistorico($criarHistoricoRequest);
        return MovimentacaoEstoque::create(
            [
                'loja_id' => $lojaId,
                'status_id' => $statusId,
                'usuario_id' => $usuarioId,
                'tipo_movimentacao_estoque_id' => $tipo_movimentacao_estoque_id,
                'observacao' => $observacao
            ]
        )
            // ->setAudit($criarHistoricoRequest)
        ;
    }

     public static function atualizaMovimentacaoEstoque(
        $id,
        $lojaId,
        $statusId,
        $usuarioId,
        $tipo_movimentacao_estoque_id,
        CriarHistoricoRequest $criarHistoricoRequest,
        $observacao = null
    ) {
        $movientacaoEstoque = MovimentacaoEstoque::find($id);
        MovimentacaoEstoque::setHistorico($criarHistoricoRequest);
        $movientacaoEstoque->update([
            'loja_id' => $lojaId,
            'status_id' => $statusId,
            'usuario_id' => $usuarioId,
            'tipo_movimentacao_estoque_id' => $tipo_movimentacao_estoque_id,
            'observacao' => $observacao
        ]);

        return $movientacaoEstoque;
    }

    public static function verificarExisteciaDeMovimentacaoEstoqueEmAberto(
        $lojaId
    ) {
        return MovimentacaoEstoque::with('movimentacao_estoque_itens')->where('loja_id', $lojaId)->where('status_id', config('config.status.aberto'))->where('usuario_id', auth()->user()->getUserModulo->id)->first();
    }

    public static function createMovimentacaoEstoqueItem(
        $estoque_id,
        $movimentacaoId,
        $tipo_movimentacao_estoque_id,
        $quantidade,
        $ativo,
        CriarHistoricoRequest $criarHistoricoRequest
    ) {
        MovimentacaoEstoqueItem::setHistorico($criarHistoricoRequest);
        return MovimentacaoEstoqueItem::create(
            [
                "estoque_id" => $estoque_id,
                "movimentacao_id" => $movimentacaoId,
                "tipo_movimentacao_estoque_id" => $tipo_movimentacao_estoque_id,
                "quantidade_movimentada" => $quantidade,
                "ativo" => $ativo
            ]
        )
            // ->setAudit($criarHistoricoRequest)
        ;
    }

    public static function updateMovimentacaoEstoqueItem(
        $id,
        $estoque_id,
        $movimentacaoId,
        $tipo_movimentacao_estoque_id,
        $quantidade,
        $ativo,
        CriarHistoricoRequest $criarHistoricoRequest
    ) {
        $movimentacao_item = MovimentacaoEstoqueItem::withTrashed()->find($id);

        MovimentacaoEstoqueItem::setHistorico($criarHistoricoRequest);
        // Se o item foi deletado logicamente, inclui a restauração no update
        if ($movimentacao_item->trashed()) {
            $movimentacao_item->restore();  // Restaura o item
        }
        $movimentacao_item->update([
            "estoque_id" => $estoque_id,
            "movimentacao_id" => $movimentacaoId,
            "tipo_movimentacao_estoque_id" => $tipo_movimentacao_estoque_id,
            "quantidade_movimentada" => $quantidade,
            "ativo" => $ativo
        ]);
        return $movimentacao_item;
    }

    public static function atualizarAtivoMovimentacaoEstoqueItem(array $movimentacao_item_ids, CriarHistoricoRequest $criarHistoricoRequest)
    {
        MovimentacaoEstoqueItem::setHistorico($criarHistoricoRequest);
        return MovimentacaoEstoqueItem::whereIn('id', $movimentacao_item_ids)->get()->each(function ($item) {
            $item->update(['ativo' => 1]);
        });
    }

    public static function deletarMovimentacaoEstoqueItem(
        int $id,
        CriarHistoricoRequest $criarHistoricoRequest
    ) {
        $movimentacao_item = MovimentacaoEstoqueItem::find($id);
        MovimentacaoEstoqueItem::setHistorico($criarHistoricoRequest);
        // $movimentacao_item->setAudit($criarHistoricoRequest);
        $movimentacao_item->delete();
        return $movimentacao_item;
    }

    public static function getTipoMovimentacoes()
    {
        return TipoMovimentacaoEstoque::all();
    }

    public static function getMovimentacaoItemByEstoqueIdAndMovimentacao(int $movimentacao_id, int $estoque_id, ?callable $callback = null)
    {
        return MovimentacaoEstoqueItem::where('movimentacao_id', $movimentacao_id)->where('estoque_id', $estoque_id)->where('ativo', 0)
        ->when($callback, function ($query) use ($callback) {
            // Passamos a query diretamente para o callback
            return $callback($query);
        })->first();
    }
}
