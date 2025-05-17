<?php

namespace Modules\Mercado\Database\Seeders\baseFake;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Mercado\Entities\Cliente;

class CreateCreditoClienteFakeSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::connection(config('database.connections.mercado.database'))->table('credito_cliente')->truncate();

        // foreach (self::getClientes() as $key => $clientes) {
            DB::connection(config('database.connections.mercado.database'))->table('credito_cliente')->insert(self::getClientes());
        // }

        DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private static function getClientes()
    {
        $creditoClientes = [];

        foreach (Cliente::all() as $cliente) {
            $creditoClientes[] = [
                'cliente_id' => $cliente->id,
                'credito_loja' => 100000, // R$ 1.000,00
                'credito_loja_usado' => null, // Nenhum crédito usado inicialmente
            ];
        }
        return $creditoClientes;
    }
}
