<?php

namespace Modules\Mercado\Database\Seeders\baseFake;

use App\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Mercado\Entities\Cliente;

class CreateCreditoClienteFakeSeed extends Seeder
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

        DB::connection($connection)->table('credito_cliente')->truncate();

        // foreach (self::getClientes() as $key => $clientes) {
            DB::connection($connection)->table('credito_cliente')->insert(self::getClientes());
            // }

            $this->enableForeignKeys($connection);
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
