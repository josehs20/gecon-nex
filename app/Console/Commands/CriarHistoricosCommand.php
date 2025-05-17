<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
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
        $historicoService = new HistoricoServices(config('database.connections.mercado.database'));
        $historicoService->criarAllTabelasHist()->criaHistoricoInicial();
        $this->info('Historicos das tabelas criado com sucesso, banco realizado operação:'.config('database.connections.mercado.database'));
    }                   
}
