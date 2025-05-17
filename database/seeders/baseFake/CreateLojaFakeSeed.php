<?php

namespace Database\Seeders\baseFake;

use App\Models\Empresa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateLojaFakeSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lojas')->insert(self::getLojas());
    }

    private static function getLojas()
    {
        $empresas = Empresa::get();
        $lojas = [];
        foreach ($empresas as $key => $e) {
            for ($i = 1; $i <= 2; $i++) {
                $lojas[] = [
                    'nome' => "loja {$i}" ,
                    'empresa_id' => $e->id,
                    'matriz' => $i == 1 ? true : false ,
                    'cnpj' => $i == 1 ? $e->cnpj : str_pad(rand(1, 99999999999999), 14, '0', STR_PAD_LEFT),
                    'modulo_id' => config('config.modulos.mercado'),
                    'status_id' => config('config.status.em_dia')
                ];
            }
        }


        return $lojas;
    }
}
