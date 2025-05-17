<?php

namespace Modules\Mercado\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateTipoImpostosSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        self::getTipoImposto();
        DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::connection(config('database.connections.mercado.database'))->table('tipo_imposto')->truncate();
        DB::connection(config('database.connections.mercado.database'))->table('tipo_imposto')->insert(self::getTipoImposto());

        DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function getTipoImposto()
    {
        $tipo_arquivos = config('config.impostos');
        $array = [];
        foreach ($tipo_arquivos as $nome => $k) {
            $array[] = [
                'id' => $k,
                'nome' => $nome,
                'descricao' => ''
            ];
        }

        return $array;
    }
}
