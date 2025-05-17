<?php

namespace Modules\Mercado\Application;

use Illuminate\Database\Eloquent\Model;
use Modules\Mercado\UseCases\Historicos\CriarHistorico;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class CriarHistoricoApplication
{
    public static function criarHistorico(CriarHistoricoRequest $request, Model $model)
    {
        $interact = new CriarHistorico($request, $model);
        return $interact->handle();
    }
}
