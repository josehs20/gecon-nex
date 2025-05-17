<?php

namespace Modules\Mercado\Http\Controllers;

use App\Services\MultiDatabaseTransactions;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class ControllerBaseMercado extends Controller
{
    //para usar as transactions em varios bancos
    private MultiDatabaseTransactions $dbs;
    public function __construct()
    {
        $this->dbs = new MultiDatabaseTransactions([
            config('database.connections.gecon.database'),
            config('database.connections.mercado.database'),
            config('database.connections.historicos.database')
        ]);
    }

    public function getDb()
    {
        return $this->dbs;
    }

    public function getCriarHistoricoRequest(Request $request)
    {
        $acao_id = $request->route('acao_id');
        $processo_id = $request->attributes->get('processo_id');
        $usuario_id =  auth()->user()->getUserModulo->id;
        $loja_id =  auth()->user()->getUserModulo->loja_id;

        if ($acao_id == null) {
            throw new Exception("Informe a ação que o usuário se encontra.", 1);
        }
        if ($processo_id == null) {
            throw new Exception("Informe o processo que o usuário se encontra.", 1);
        }

        return new CriarHistoricoRequest($processo_id, $acao_id, $usuario_id, null, $loja_id);
    }

    public function download_arquivo()
    {
        return true;
    }
}
