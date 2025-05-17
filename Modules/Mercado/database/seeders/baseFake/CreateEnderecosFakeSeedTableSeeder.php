<?php

namespace Modules\Mercado\Database\Seeders\baseFake;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateEnderecosFakeSeedTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::connection(config('database.connections.mercado.database'))->table('enderecos')->truncate();

        DB::connection(config('database.connections.mercado.database'))->table('enderecos')->insert(self::getEnderecos());
        DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private static function getEnderecos()
    {
        return [
            'logradouro' => 'Logradouro Fake',
            'numero' => 10,
            'bairro' => 'Bairro Fake',
            'cidade' => 'Cidade Fake',
            'uf' => 'ES',
            'cep' => 111111111,
            'complemento' => 'Complementos Fake',
        ];
    }
}
