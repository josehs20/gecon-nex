<?php

namespace Modules\Mercado\Application;

use Modules\Mercado\UseCases\Gerenciamento\Recebimento\AtualizaStatus;
use Modules\Mercado\UseCases\Gerenciamento\Recebimento\Receber;
use Modules\Mercado\UseCases\Gerenciamento\Recebimento\ReceberNF;
use Modules\Mercado\UseCases\Gerenciamento\Recebimento\Requests\ReceberNFRequest;
use Modules\Mercado\UseCases\Gerenciamento\Recebimento\Requests\ReceberRequest;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class RecebimentoApplication
{
    public static function receber(ReceberRequest $request)
    {
        $interact = new Receber($request);
        return $interact->handle();
    }

    public static function atualizaStatus(int $id, int $status_id, CriarHistoricoRequest $criarHistoricoRequest)
    {
        $interact = new AtualizaStatus($id, $status_id, $criarHistoricoRequest);
        return $interact->handle();
    }

    public static function creceberNF(ReceberNFRequest $request)
    {
        $interact = new ReceberNF($request);
        return $interact->handle();
    }
}
