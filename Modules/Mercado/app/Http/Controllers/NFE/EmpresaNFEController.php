<?php

namespace Modules\Mercado\Http\Controllers\NFE;

use App\Services\GtinService;
use App\Services\NFEIOService;
use Modules\Mercado\Entities\Venda;
use Modules\Mercado\Http\Controllers\ControllerBaseMercado;

class EmpresaNFEController extends ControllerBaseMercado
{
    public function index()
    {
        // $venda = Venda::first();
        // $nfeService = new NFEIOService();
        // // $a = $nfeService->listInscricaoEstadual($venda->loja_id);
        // dd($venda);
        // $nfeService->emitirNFCE($venda);
        // dd($nfeService);
        $a = new GtinService();
        $a->getGtin('7898236720020');
    }
}
