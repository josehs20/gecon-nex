<?php

namespace Modules\Mercado\Repository\UnidadeMedida;

use Modules\Mercado\Entities\UnidadeMedida;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class UnidadeMedidaRepository
{
    public static function create(
        CriarHistoricoRequest $criarHistoricoRequest,
        string $descricao,
        string $sigla,
        bool $pode_ser_float,
        int $empresa_master_cod
    ): ?UnidadeMedida {
        UnidadeMedida::setHistorico($criarHistoricoRequest);
        return UnidadeMedida::create([
            'descricao' => $descricao,
            'sigla' => $sigla,
            'pode_ser_float' => $pode_ser_float,
            'empresa_master_cod' => $empresa_master_cod
        ]);
    }

    public static function editar(
        CriarHistoricoRequest $criarHistoricoRequest,
        int $id,
        string $descricao,
        string $sigla,
        bool $pode_ser_float,
        int $empresa_master_cod
    ): ?UnidadeMedida {
        $un = UnidadeMedida::find($id);
        UnidadeMedida::setHistorico($criarHistoricoRequest);
        $un->update([
            'descricao' => $descricao,
            'sigla' => $sigla,
            'pode_ser_float' => $pode_ser_float,
            'empresa_master_cod' => $empresa_master_cod

        ]);
        return $un;
    }

    public static function getUnByDescricao(
        string $descricao
    ): ?UnidadeMedida {
        return UnidadeMedida::where('descricao', $descricao)->where('empresa_master_cod', auth()->user()->empresa_id)->first();
    }

    public static function getUnLikeDescricao(
        ?string $descricao = null
    ) {
        return UnidadeMedida::where('descricao', 'like', '%' . $descricao . '%')->where('empresa_master_cod', auth()->user()->empresa_id)
        ->get();
    }

    public static function getUnById(
        int $id
    ) {
        return UnidadeMedida::find($id);
    }
}
