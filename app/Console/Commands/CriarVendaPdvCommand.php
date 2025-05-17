<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Mercado\Entities\Caixa;

class CriarVendaPdvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'criar:vendas';

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
        $caixas = Caixa::where('');
    }
}
