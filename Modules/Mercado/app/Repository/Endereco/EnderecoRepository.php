<?php

namespace Modules\Mercado\Repository\Endereco;

use Modules\Mercado\Entities\Endereco;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class EnderecoRepository
{
    public static function create(
        CriarHistoricoRequest $criarHistoricoRequest,
        string $logradouro,
        ?string $numero = null,
        string $cidade,
        string $bairro,
        string $uf,
        string $cep,
        ?string $complemento = null
    ): ?Endereco {
        Endereco::setHistorico($criarHistoricoRequest);
        return Endereco::create([
            'logradouro' => $logradouro,
            'numero' => $numero,
            'cidade' => $cidade,
            'bairro' => $bairro,
            'uf' => $uf,
            'cep' => $cep,
            'complemento' => $complemento,
        ]);
    }

    public static function update(
        int $endereco_id,
        CriarHistoricoRequest $criarHistoricoRequest,
        string $logradouro,
        ?string $numero = null,
        string $cidade,
        string $bairro,
        string $uf,
        string $cep,
        ?string $complemento = null
    ): ?Endereco {
        $endereco = Endereco::find($endereco_id);
        Endereco::setHistorico($criarHistoricoRequest);
        $endereco->update([
            'logradouro'   =>    $logradouro,
            'numero'       =>    $numero,
            'cidade'       =>    $cidade,
            'bairro'       =>    $bairro,
            'uf'           =>    $uf,
            'cep'          =>    $cep,
            'complemento'  =>    $complemento,
        ]);
        return $endereco;
    }

    public static function getEnderecoById(
        int $endereco_id
    ): ?Endereco{
        return Endereco::find($endereco_id);
    }


}
