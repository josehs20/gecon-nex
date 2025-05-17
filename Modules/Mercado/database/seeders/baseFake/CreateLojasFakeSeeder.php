<?php

namespace Modules\Mercado\Database\Seeders\baseFake;

use App\Models\Empresa;
use App\Models\Loja;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Mercado\Entities\Endereco;

class CreateLojasFakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::connection(config('database.connections.mercado.database'))->table('lojas')->truncate();

        DB::connection(config('database.connections.mercado.database'))->table('lojas')->insert(self::getLojas());
        DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private static function getLojas()
    {
        $data = [];
        $lojas = Loja::where('modulo_id', config('config.modulos.mercado'))->get();
        $enderecoPadrao = Endereco::first();

        foreach ($lojas as $key => $l) {

                $data[] = [
                    'nome' => $l->nome,
                    'empresa_master_cod' => $l->empresa_id,
                    'loja_master_cod' => $l->id,
                    'endereco_id' => $enderecoPadrao->id,
                    'status_id' => config('config.status.ativo'),
                    'cnpj' => $l->cnpj
                ];
        }

        return $data;
    }
}
