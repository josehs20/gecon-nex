<?php

namespace Modules\Mercado\Database\Seeders\baseFake;

use App\Models\Empresa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateUnidadeMedidaFakeSeedTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::connection(config('database.connections.mercado.database'))->table('unidade_medida')->truncate();
        $empresas = Empresa::get();
        foreach ($empresas as $key => $value) {
            DB::connection(config('database.connections.mercado.database'))->table('unidade_medida')->insert(self::getUn($value->id));
        }
     

        DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function getUn($empresa_id)
    {
        return [
            [
                'sigla' => 'UN',
                'descricao' => 'Unidade',
                'pode_ser_float' => false,
                'empresa_master_cod' => $empresa_id
            ],
            [
                'sigla' => 'Kg',
                'descricao' => 'Quilograma',
                'pode_ser_float' => true,
                'empresa_master_cod' => $empresa_id
            ],
            [
                'sigla' => 'Cx',
                'descricao' => 'Caixa',
                'pode_ser_float' => false,
                'empresa_master_cod' => $empresa_id
            ],
            [
                'sigla' => 'L',
                'descricao' => 'Litro',
                'pode_ser_float' => true,
                'empresa_master_cod' => $empresa_id
            ],
            [
                'sigla' => 'Ml',
                'descricao' => 'Mililitro',
                'pode_ser_float' => true,
                'empresa_master_cod' => $empresa_id
            ],
            [
                'sigla' => 'G',
                'descricao' => 'Grama',
                'pode_ser_float' => true,
                'empresa_master_cod' => $empresa_id
            ],
            [
                'sigla' => 'M',
                'descricao' => 'Metro',
                'pode_ser_float' => true,
                'empresa_master_cod' => $empresa_id
            ],
            [
                'sigla' => 'Pc',
                'descricao' => 'Peça',
                'pode_ser_float' => false,
                'empresa_master_cod' => $empresa_id
            ],
            [
                'sigla' => 'Duz',
                'descricao' => 'Dúzia',
                'pode_ser_float' => false,
                'empresa_master_cod' => $empresa_id
            ],
            [
                'sigla' => 'Pcte',
                'descricao' => 'Pacote',
                'pode_ser_float' => false,
                'empresa_master_cod' => $empresa_id
            ],
            [
                'sigla' => 'Cm',
                'descricao' => 'Centímetro',
                'pode_ser_float' => true,
                'empresa_master_cod' => $empresa_id
            ],
            [
                'sigla' => 'M2',
                'descricao' => 'Metro Quadrado',
                'pode_ser_float' => true,
                'empresa_master_cod' => $empresa_id
            ],
        ];
    }
}
