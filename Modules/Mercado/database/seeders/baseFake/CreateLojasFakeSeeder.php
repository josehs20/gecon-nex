<?php

namespace Modules\Mercado\Database\Seeders\baseFake;

use App\Models\Empresa;
use App\Models\Loja;
use App\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Mercado\Entities\Endereco;

class CreateLojasFakeSeeder extends Seeder
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
        DB::connection($connection)->table('lojas')->truncate();

        DB::connection($connection)->table('lojas')->insert(self::getLojas());
        $this->enableForeignKeys($connection);
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
