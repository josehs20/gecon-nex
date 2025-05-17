<?php

namespace Database\Seeders;

use App\Models\Modulo;
use App\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateModulosSeed extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $connection = 'gecon';

        $this->disableForeignKeys($connection);

        DB::connection($connection)->table('modulos')->truncate();
        DB::connection($connection)->table('modulos')->insert(self::getModulos());

        $this->enableForeignKeys($connection);
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
