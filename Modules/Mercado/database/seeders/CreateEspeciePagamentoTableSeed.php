<?php

namespace Modules\Mercado\Database\Seeders;

use App\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateEspeciePagamentoTableSeed extends Seeder
{
    use DisableForeignKeys;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $connection = 'mercado';

        $this->disableForeignKeys($connection);
        DB::connection($connection)->table('especie_pagamento')->truncate();
        DB::connection($connection)->table('especie_pagamento')->insert(self::getEspeciePagemetos());
        $this->enableForeignKeys($connection);

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
