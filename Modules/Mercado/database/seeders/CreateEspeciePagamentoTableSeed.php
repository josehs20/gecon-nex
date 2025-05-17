<?php

namespace Modules\Mercado\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Mercado\Entities\Loja;

class CreateEspeciePagamentoTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::connection(config('database.connections.mercado.database'))->table('especie_pagamento')->truncate();
        DB::connection(config('database.connections.mercado.database'))->table('especie_pagamento')->insert(self::getEspeciePagemetos());

        DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function getEspeciePagemetos()
    {
        $especies = config('config.especie_pagamento');
        $array = [];
        foreach ($especies as $key => $especie) {
            $array[] = $especie;
        }
        
        return $array;
    }
}
