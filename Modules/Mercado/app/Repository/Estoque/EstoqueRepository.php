<?php

namespace Modules\Mercado\Repository\Estoque;

use Modules\Mercado\Entities\Estoque;
use Modules\Mercado\Entities\MovimentacaoEstoque;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class EstoqueRepository
{
    public static function create(
        $custo,
        $preco,
        $produto_id,
        $loja_id,
        $quantidade_total,
        $quantidade_disponivel,
        $quantidade_minima,
        $quantidade_maxima,
        $localizacao,
        CriarHistoricoRequest $criarHistoricoRequest
    ): ?Estoque {
        Estoque::setHistorico($criarHistoricoRequest);
        return Estoque::create([
            'custo' => $custo,
            'preco' => $preco,
            'produto_id' => $produto_id,
            'loja_id' => $loja_id,
            'quantidade_total' => $quantidade_total,
            'quantidade_disponivel' => $quantidade_disponivel,
            'quantidade_minima' => $quantidade_minima,
            'quantidade_maxima' => $quantidade_maxima,
            'localizacao' => $localizacao,
        ]);
    }

    public static function updateQtdEstoque(
        $id,
        $custo,
        $preco,
        $produto_id,
        $loja_id,
        $quantidade_total,
        $quantidade_disponivel,
        $quantidade_minima,
        $quantidade_maxima,
        $localizacao,
        CriarHistoricoRequest $criarHistoricoRequest
    ) {
        $estoque = Estoque::find($id);
        Estoque::setHistorico($criarHistoricoRequest);

        $estoque->update([
            'custo' => $custo,
            'preco' => $preco,
            'produto_id' => $produto_id,
            'loja_id' => $loja_id,
            'quantidade_total' => $quantidade_total,
            'quantidade_disponivel' => $quantidade_disponivel,
            'quantidade_minima' => $quantidade_minima,
            'quantidade_maxima' => $quantidade_maxima,
            'localizacao' => $localizacao,
        ]);
        return $estoque;
    }

    public static function updateQtdMinMax(
        $id,
        $quantidade_maxima,
        $quantidade_minima,
        $localizacao = null,
        CriarHistoricoRequest $criarHistoricoRequest
    ) {
        $estoque = Estoque::find($id);
        Estoque::setHistorico($criarHistoricoRequest);

        $estoque->update([
            'quantidade_maxima' => $quantidade_maxima,
            'quantidade_minima' => $quantidade_minima,
            'localizacao' => $localizacao ?? $estoque->localizacao,
        ]);

        return $estoque;
    }
    public static function getEstoquesByProduto($search = '')
    {
        return Estoque::with('produto')->where('loja_id', auth()->user()->getUserModulo->loja_id)->whereHas('produto', function ($q) use ($search) {
            $q->where('nome', 'like', '%' . $search . '%');
        })->get();
    }

    public static function getEstoqueById($id)
    {
        return Estoque::find($id);
    }

    public static function getEstoqueByIds(array $ids)
    {
        return Estoque::with('produto')->whereIn('id', $ids)->where('loja_id', auth()->user()->usuarioMercado->loja_id)->get();
    }

    public static function updateQtdDisponivel(
        $id,
        $quantidade_disponivel,
        $quantidade_total,
        CriarHistoricoRequest $criarHistoricoRequest
    ) {
        Estoque::setHistorico($criarHistoricoRequest);
        $estoque = Estoque::find($id);
        $estoque->update([
            'quantidade_total' => $quantidade_total,
            'quantidade_disponivel' => $quantidade_disponivel,
        ]);

        // $estoque->setAudit($criarHistoricoRequest);

        return $estoque;
    }

    public static function updatePrecos(
        $id,
        $preco,
        $custo,
        CriarHistoricoRequest $criarHistoricoRequest
    ) {
        $estoque = Estoque::find($id);
        Estoque::setHistorico($criarHistoricoRequest);
        $estoque->update([
            'preco' => $preco,
            'custo' => $custo,
        ]);

        return $estoque;
    }

    public static function getTodosOsEstoques($loja_id)
    {
        return Estoque::with('produto.fabricante', 'produto.unidade_medida', 'loja')->whereHas('produto')->where('loja_id', $loja_id)->get();
    }



    //---------------------Recebiemnto-------------------//

    public static function getRecebimentoAberto($usuario_id, $loja_id)
    {
        return MovimentacaoEstoque::where('usuario_id', $usuario_id)->where('loja_id', $loja_id)->where('status_id', config('config.status.recebimento_iniciado'))->first();
    }

    public static function getRecebimentosByUsuario($usuario_id, $loja_id)
    {
        return MovimentacaoEstoque::with(['usuario' => function ($q) {
            $q->with('master');
        }])->where('usuario_id', $usuario_id)->where('loja_id', $loja_id)
            ->where('tipo_movimentacao_estoque_id',  config('config.tipo_movimentacao_estoque.recebimento'))->get();
    }

    //-----------------NCM--------------------------//
    public static function atualizaNCM($estoque_id, $ncm_id)
    {
        $estoque = Estoque::find($estoque_id);
        $estoque->update([
            'ncm_id' => $$ncm_id
        ]);
        return $estoque;
    }
}
