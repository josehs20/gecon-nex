<?php

namespace Modules\Mercado\Repository\Balanco;

use Modules\Mercado\Entities\Balanco;
use Modules\Mercado\Entities\BalancoItem;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class BalancoRepository
{
    public static function getTodosBalancos(int $loja_id)
    {
        return Balanco::with(['balanco_itens.estoque.produto.unidade_medida', 'balanco_itens.estoque.produto.fabricante'])->where('loja_id', $loja_id)->get();
    }

    public static function getBalancoPorId(int $balanco_id)
    {
        return Balanco::with(['balanco_itens.estoque.produto.unidade_medida', 'balanco_itens.estoque.produto.fabricante'])->where('id', $balanco_id)->first();
    }

    public static function verificarExistenciaBalancoEmAberto(int $loja_id)
    {
        return Balanco::where('loja_id', $loja_id)->where('usuario_id', auth()->user()->getUserModulo->id)->where('status_id', config('config.status.aberto'))->first();
    }

    public static function createBalanco(
        int $loja_id,
        int $status_id,
        int $usuario_id,
        $observacao = null,
        CriarHistoricoRequest $criarHistoricoRequest
    ): ?Balanco {
        Balanco::setHistorico($criarHistoricoRequest);
        return Balanco::create([
            'loja_id' => $loja_id,
            'status_id' => $status_id,
            'usuario_id' => $usuario_id,
            'observacao' => $observacao,
        ]);
    }
    public static function updateBalanco(
        int $id,
        int $loja_id,
        int $status_id,
        int $usuario_id,
        $observacao = null,
        CriarHistoricoRequest $criarHistoricoRequest
    ): ?Balanco {
        $balanco = Balanco::find($id);
        Balanco::setHistorico($criarHistoricoRequest);
        $balanco->update([
            'loja_id' => $loja_id,
            'status_id' => $status_id,
            'usuario_id' => $usuario_id,
            'observacao' => $observacao,
        ]);
        return $balanco;
    }

    public static function getBalancoItensPorBalancoId(int $balanco_id)
    {
        return BalancoItem::with('estoque.produto.unidade_medida', 'estoque.produto.fabricante')->where('balanco_id', $balanco_id)->get();
    }

    public static function createBalancoItem(
        int $estoque_id,
        int $loja_id,
        float $quantidade_estoque_sistema,
        float $quantidade_estoque_real,
        float $quantidade_resultado_operacional,
        int $balanco_id,
        int $ativo,
        int $tipo_movimentacao_id,
        CriarHistoricoRequest $criarHistoricoRequest
    ): BalancoItem {
        BalancoItem::setHistorico($criarHistoricoRequest);

        return BalancoItem::create([
            'estoque_id' => $estoque_id,
            'loja_id' => $loja_id,
            'quantidade_estoque_sistema' => $quantidade_estoque_sistema,
            'quantidade_estoque_real' => $quantidade_estoque_real,
            'quantidade_resultado_operacional' => $quantidade_resultado_operacional,
            'balanco_id' => $balanco_id,
            'ativo' => $ativo,
            'tipo_movimentacao_estoque_id' => $tipo_movimentacao_id
        ]);
    }

    public static function updateBalancoItem(
        int $id,
        int $estoque_id,
        int $loja_id,
        float $quantidade_estoque_sistema,
        float $quantidade_estoque_real,
        float $quantidade_resultado_operacional,
        int $balanco_id,
        int $ativo,
        int $tipo_movimentacao_id,
        CriarHistoricoRequest $criarHistoricoRequest
    ): BalancoItem {
        $balancoItem = BalancoItem::withTrashed()->find($id);

        BalancoItem::setHistorico($criarHistoricoRequest);
        // Se o item foi deletado logicamente, inclui a restauração no update
        if ($balancoItem->trashed()) {
            $balancoItem->restore();  // Restaura o item
        }

        // Ignora o SoftDeletes e seta manualmente o `deleted_at`
        $balancoItem->update([
            'estoque_id' => $estoque_id,
            'loja_id' => $loja_id,
            'quantidade_estoque_sistema' => $quantidade_estoque_sistema,
            'quantidade_estoque_real' => $quantidade_estoque_real,
            'quantidade_resultado_operacional' => $quantidade_resultado_operacional,
            'balanco_id' => $balanco_id,
            'ativo' => $ativo,
            'tipo_movimentacao_estoque_id' => $tipo_movimentacao_id
        ]);
        return $balancoItem;
    }

    public static function verificarExistenciaProdutoBalancoItem(int $balanco_id, int $estoque_id, ?callable $callback = null)
    {
        return BalancoItem::where('balanco_id', $balanco_id)
            ->where('estoque_id', $estoque_id)
            // Usamos 'when' para aplicar a cláusula extra, se o callback for passado
            ->when($callback, function ($query) use ($callback) {
                // Passamos a query diretamente para o callback
                return $callback($query);
            })
            ->first();
    }

    public static function deleteBalancoItem(
        int $id,
        CriarHistoricoRequest $criarHistoricoRequest
    ) {
        $movimentacao_item = BalancoItem::find($id);
        BalancoItem::setHistorico($criarHistoricoRequest);
        $movimentacao_item->delete();
        return $movimentacao_item;
    }

    public static function atualizarStatusBalanco(
        int $balanco_id,
        int $status_id,
        CriarHistoricoRequest $criarHistoricoRequest,
        $observacao = null
    ) {
        $balanco = Balanco::find($balanco_id);
        Balanco::setHistorico($criarHistoricoRequest);
        $balanco->update([
            'status_id' => $status_id,
            'observacao' => $observacao ?? $balanco->observacao
        ]);
        // $balanco->setAudit($criarHistoricoRequest);
        return $balanco;
    }

    public static function atualizarAtivoBalancoItem(array $balanco_item_ids, CriarHistoricoRequest $criarHistoricoRequest)
    {
        BalancoItem::setHistorico($criarHistoricoRequest);
        return BalancoItem::whereIn('id', $balanco_item_ids)->update(['ativo' => 1]);
    }

    public static function deleteBalanco($id, CriarHistoricoRequest $criarHistoricoRequest)
    {
        Balanco::setHistorico($criarHistoricoRequest);
        $balanco = Balanco::find($id);
        $balanco->update(['status_id'=> config('config.status.cancelado')]);
        // $balanco->delete();
        return $balanco;
    }
}
