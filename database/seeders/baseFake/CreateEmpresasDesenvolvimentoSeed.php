<?php

namespace Database\Seeders\baseFake;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateEmpresasDesenvolvimentoSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('empresas')->insert(self::getEmpresas());
    }

    private static function getEmpresas()
    {
        $empresas = [];

        for ($i = 1; $i <= 25; $i++) {
            $empresas[] = [
                'razao_social' => "empresa {$i} ME",
                'nome_fantasia' => "empresa {$i}",
                'cnpj' => str_pad(rand(1, 99999999999999), 14, '0', STR_PAD_LEFT), // CNPJ aleatório com 14 dígitos
                'ativo' => 1,
                'status_id' => config('config.status.em_dia')
            ];
        }

        return $empresas;
    }

}
