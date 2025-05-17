<?php

namespace Modules\Mercado\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateTipoArquivoSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::connection(config('database.connections.mercado.database'))->table('tipo_arquivo')->truncate();
        DB::connection(config('database.connections.mercado.database'))->table('tipo_arquivo')->insert(self::getTipoArquivos());

        DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function getTipoArquivos()
    {
        $tipo_arquivos = config('config.tipo_arquivo');
        $array = [];
        foreach ($tipo_arquivos as $descricao => $id) {
            $descricaoFormatada = strtoupper(str_replace('_', ' ', $descricao));

            $array[] = [
                'id' => $id,
                'descricao' => $descricaoFormatada
            ];
        }
        
        return $array;
    }
}
