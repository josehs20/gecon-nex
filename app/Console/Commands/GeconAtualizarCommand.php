<?php

namespace App\Console\Commands;

use Database\Seeders\AcoesGeconSeed;
use Database\Seeders\permissoes\ProcessosUsuariosSeed;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Modules\Mercado\Database\Seeders\AcoesMercadoSeed;
use Modules\Mercado\Database\Seeders\StatusMercadoSeed;

class GeconAtualizarCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gecon:atualizar';

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
        system('php artisan config:cache');
        system('php artisan route:cache');

        // system('php artisan config:clear');
        Cache::forget('hist_cache');

        $this->info('Tirando sistema do ar');
        system('php artisan down');

        $this->info('rodando migrations');
        system('php artisan migrate');

        $this->info('Atualizando modulo gecon');
        system('php artisan db:seed --class="Database\Seeders\DatabaseSeeder"');
        $this->info('Modulo gecon atualizado');

        $this->info('Atualizando modulo mercado');
        system('php artisan db:seed --class="Modules\Mercado\Database\Seeders\MercadoDatabaseSeeder"');

        $this->info('Validando colunas para softDeletes');
        system('php artisan gecon:softDelete-tables');
        $this->info('Validação concluída');

        $this->info('Modulo mercado atualizado');

        $this->info('Colocando sistema do ar');
        system('php artisan up');

    }
}
