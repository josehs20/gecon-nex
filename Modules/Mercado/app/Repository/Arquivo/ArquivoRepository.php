<?php

namespace Modules\Mercado\Repository\Arquivo;

use Modules\Mercado\Entities\Arquivo;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class ArquivoRepository
{
    public static function create(
        $tipo_arquivo_id,
        $path,
        $loja_id,
        CriarHistoricoRequest $criarHistoricoRequest
    ) {
        Arquivo::setHistorico($criarHistoricoRequest);
        return Arquivo::create([
            'tipo_arquivo_id' => $tipo_arquivo_id,
            'path' => $path,
            'loja_id' => $loja_id,
        ]);
    }

    public static function getArquivoByID($id) {
        return Arquivo::find($id);
    }
}
