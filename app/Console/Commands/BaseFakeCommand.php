<?php

namespace App\Console\Commands;

use Database\Seeders\CreateEmpresasDesenvolvimentoSeed;
use Database\Seeders\CreateModulosSeed;
use Database\Seeders\CreateTipoUsuariosSeed;
use Database\Seeders\CreateUsuariosDesenvolvimentoSeed;
use Illuminate\Console\Command;
use Modules\Mercado\Database\Seeders\CreateCaixasTableSeed;
use Modules\Mercado\Database\Seeders\CreateEnderecosFakeSeedTableSeeder;
use Modules\Mercado\Database\Seeders\CreateFornecedoresFakeSeedTableSeeder;
use Modules\Mercado\Database\Seeders\CreateLojasFakeSeeder;
use Modules\Mercado\Database\Seeders\CreateProdutoFakeSeed;
use Modules\Mercado\Database\Seeders\StatusMercadoSeed;

class BaseFakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'base:fake';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'insere registros fakes para desenvolvimento';

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
     * @return int
     */
    public function handle()
    {
        system('php artisan db:seed --class="Database\Seeders\baseFake\DatabaseFakeSeeder"');

        system('php artisan db:seed --class="Modules\Mercado\Database\Seeders\baseFake\MercadoDatabaseFakeSeeder"');
    }
}
