<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Jhslib\HistoricoService\Services\HistoricoServices;

class CriarHistoricosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:historico';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $drive = DB::connection('mercado')->getDriverName();
        if ($drive == 'sqlite') {
            $this->info('Sistema desktop não precisa de histórico.');
            return;
        }
        $historicoService = new HistoricoServices('mercado');
        $historicoService->criarAllTabelasHist()->criaHistoricoInicial();
        $this->info('Históricos das tabelas criado com sucesso, banco realizado operação:' . 'mercado');
    }
}
