<?php

namespace Modules\Mercado\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcoesMercadoSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::connection(config('database.connections.mercado.database'))->table('acoes')->truncate();

        DB::connection(config('database.connections.mercado.database'))->table('acoes')->insert(self::getAcoes());

        DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private static function getAcoes()
    {
        return config('config.acoes');
    }
}
