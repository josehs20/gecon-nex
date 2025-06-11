<?php

namespace Modules\Mercado\Application;

use Modules\Mercado\Repository\Recebimento\RecebimentoRepository;
use Modules\Mercado\UseCases\Gerenciamento\Recebimento\Receber;
use Modules\Mercado\UseCases\Gerenciamento\Recebimento\Requests\ReceberRequest;

class RecebimentoApplication
{
    public static function obterRecebimentos(int $loja_id){
        return RecebimentoRepository::obterRecebimentos($loja_id);
    }

    public static function receber(ReceberRequest $request)
    {
        $interact = new Receber($request);
        return $interact->handle();
    }
}
