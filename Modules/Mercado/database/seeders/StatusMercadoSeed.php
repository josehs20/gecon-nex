<?php

namespace Modules\Mercado\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusMercadoSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::connection(config('database.connections.mercado.database'))->table('status')->truncate();
        DB::connection(config('database.connections.mercado.database'))->table('status')->insert(self::getStatus());
        DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=1;');

    }

    private static function getStatus()
    {
        $status = [];
        $configStatus = config('config.status');

        foreach ($configStatus as $nome => $id) {
            // Substitui os sublinhados por espaços e capitaliza a primeira letra de cada palavra
            $descricaoFormatada = strtoupper(str_replace('_', ' ', $nome));

            $status[] = [
                'id' => $id,
                'descricao' => $descricaoFormatada
            ];
        }

        return $status;
    }
}
