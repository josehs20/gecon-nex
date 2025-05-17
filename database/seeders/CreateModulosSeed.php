<?php

namespace Database\Seeders;

use App\Models\Modulo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateModulosSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('modulos')->truncate();
        DB::table('modulos')->insert(self::getModulos());

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private static function getModulos() {
        $modulos = config('config.modulos');
        $modulosMapeados = [];
        foreach ($modulos as $nome => $id) {
            $modulosMapeados[] = [
                'id' => $id,
                'nome' => $nome
            ];
        }
        return $modulosMapeados;
    }
}
