<?php

namespace Modules\Mercado\Repository\Loja;

use Modules\Mercado\Entities\Loja;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class LojaRepository
{
    public static function create(
        string $nome,
        int $empresa_master_cod,
        int $loja_master_cod,
        string $cnpj,
        int $status_id,
        ?int $endereco_id = null,
        CriarHistoricoRequest $criarHistoricoRequest
    ): ?Loja {
        Loja::setHistorico($criarHistoricoRequest);
        return Loja::create([
            'nome' => $nome,
            'empresa_master_cod' => $empresa_master_cod,
            'loja_master_cod' => $loja_master_cod,
            'cnpj' => $cnpj,
            'status_id' => $status_id,
            'endereco_id' => $endereco_id
        ]);
    }

    public static function update(
        int $id,
        string $nome,
        int $empresa_master_cod,
        int $loja_master_cod,
        string $cnpj,
        int $status_id,
        ?int $endereco_id = null,
        CriarHistoricoRequest $criarHistoricoRequest
    ): ?Loja {
        $loja = Loja::find($id);
        Loja::setHistorico($criarHistoricoRequest);
        $loja->update([
            'nome' => $nome,
            'empresa_master_cod' => $empresa_master_cod,
            'loja_master_cod' => $loja_master_cod,
            'cnpj' => $cnpj,
            'status_id' => $status_id,
            'endereco_id' => $endereco_id
        ]);
        return $loja;
    }


    public static function getLojaByCnpj(string $cnpj)
    {
        return Loja::where('cnpj', $cnpj)->first();
    }

    public static function getLojaById($id)
    {
        return Loja::find($id);
    }

    public static function getLojasByEmpresaId(int $empresa_master_cod)
    {
        return Loja::where('empresa_master_cod', $empresa_master_cod)->get();
    }
}
