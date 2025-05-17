<?php

namespace App\Http\Controllers;

use App\Services\MultiDatabaseTransactions;
use Illuminate\Routing\Controller;

class ControllerBase extends Controller
{
    //para usar as transactions em varios bancos
    private MultiDatabaseTransactions $dbs;
    public function __construct()
    {
        $lite = env('DB_CONNECTION', '');
        if ($lite == 'sqlite') {
            $connections = [
                'gecon',
                'mercado',
            ];

        }else {
            $connections = [
                'gecon',
                'mercado',
                'historicos'
            ];
        }
        $this->dbs = new MultiDatabaseTransactions($connections);
    }

    public function getDb()
    {
        return $this->dbs;
    }
}
