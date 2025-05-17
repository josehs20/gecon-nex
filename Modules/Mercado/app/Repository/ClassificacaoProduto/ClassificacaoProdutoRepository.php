<?php

namespace Modules\Mercado\Repository\ClassificacaoProduto;

use Modules\Mercado\Entities\ClassificacaoProduto;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class ClassificacaoProdutoRepository
{
    public static function create(
        string $descricao,
        int $empresa_master_cod,
        CriarHistoricoRequest $criarHistoricoRequest
    ): ?ClassificacaoProduto {
        ClassificacaoProduto::setHistorico($criarHistoricoRequest);
        return ClassificacaoProduto::create([
            'descricao' => $descricao,
            'empresa_master_cod' => $empresa_master_cod
        ]);
    }

    public static function update(
        int $id,
        string $descricao,
        int $empresa_master_cod,
        CriarHistoricoRequest $criarHistoricoRequest
    ): ?ClassificacaoProduto {
        $classificacao = ClassificacaoProduto::where('id', $id)->first();
        ClassificacaoProduto::setHistorico($criarHistoricoRequest);
        $classificacao->update([
            'descricao' => $descricao,
            'empresa_master_cod' => $empresa_master_cod
        ]);

        return $classificacao;
    }

    public static function getCpByDescricao(
        string $descricao
    ): ?ClassificacaoProduto {
        return ClassificacaoProduto::where('descricao', $descricao)->where('empresa_master_cod', auth()->user()->empresa_id)->first();
    }

    public static function getCpLikeDescricao(
        ?string $descricao = null
    ) {
        return ClassificacaoProduto::where('descricao', 'like', '%' . $descricao . '%')->where('empresa_master_cod', auth()->user()->empresa_id)
        ->limit(200)
        ->get();
    }

    public static function getClassificaoById(
        int $id
    ) {
        return ClassificacaoProduto::find($id);
    }
}
