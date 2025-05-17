<?php

namespace Modules\Mercado\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateTipoMovitacaoEstoqueSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::connection(config('database.connections.mercado.database'))->table('tipo_movimentacao_estoque')->truncate();
        DB::connection(config('database.connections.mercado.database'))->table('tipo_movimentacao_estoque')->insert(self::getStatus());
        DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=1;');

    }

    private static function getStatus()
    {
        $status = [];
        $tipoMovimentacoes = config('config.tipo_movimentacao_estoque');

        foreach ($tipoMovimentacoes as $descricao => $id) {
            // Substitui os sublinhados por espaços e capitaliza a primeira letra de cada palavra
            $descricaoFormatada = strtoupper(str_replace('_', ' ', $descricao));

            $status[] = [
                'id' => $id,
                'descricao' => $descricaoFormatada
            ];
        }

        return $status;
    }
}
