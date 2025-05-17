<?php

namespace Modules\Mercado\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecursosCaixaSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::connection(config('database.connections.mercado.database'))->table('recursos')->truncate();
        DB::connection(config('database.connections.mercado.database'))->table('recursos')->insert(self::getRecursos());
        DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=1;');

    }

    private static function getRecursos()
    {
        $recursos = [];
        $configRecursos = config('config.caixa.recursos');

        foreach ($configRecursos as $nome => $r) {
            $recursos[] = [
                'id' => $r['id'],
                'nome' => $nome,
                'descricao' => $r['descricao']
            ];
        }

        return $recursos;
    }
}
