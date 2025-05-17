<?php

namespace Modules\Mercado\Database\Seeders\baseFake;

use App\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateEnderecosFakeSeedTableSeeder extends Seeder
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

        DB::connection($connection)->table('enderecos')->truncate();

        DB::connection($connection)->table('enderecos')->insert(self::getEnderecos());
        $this->enableForeignKeys($connection);
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
