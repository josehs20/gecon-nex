<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GeconInstalarCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gecon:instalar {--env=}';

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
        $this->info('Tirando sistema do ar');
        system('php artisan down');
        $this->call('instalar:base');
        $this->call('migrate');
        system('php artisan gecon:atualizar');
        if ($this->option('env') == 'dev') {
            //chama as seeders de criação

            $this->call('base:fake');

            $this->info('Base de dados para desenvolvimento criada com sucesso.');
        }
        system('php artisan gecon:atualizar');
        $this->info('Criando base historico');
        system('php artisan create:historico');
        $this->info('Colocando sistema do ar');
    }
}
