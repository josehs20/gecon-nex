<?php

namespace Modules\Mercado\Application;

use Modules\Mercado\UseCases\Arquivo\CriarArquivo;
use Modules\Mercado\UseCases\Arquivo\GeraArquivoNF;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class ArquivoApplication
{
    public static function createArquivo(mixed $arquivo, int $tipo_arquivo, int $loja_id, CriarHistoricoRequest $criarHistoricoRequest){
        $interact = new CriarArquivo($arquivo, $tipo_arquivo, $loja_id, $criarHistoricoRequest);
        return $interact->handle();
    }

    public static function geraArquivoNF(array $nota){
        $interact = new GeraArquivoNF($nota);
        return $interact->handle();
    }
}