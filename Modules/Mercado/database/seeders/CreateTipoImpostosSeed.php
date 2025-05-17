<?php

namespace Modules\Mercado\Database\Seeders;

use App\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateTipoImpostosSeed extends Seeder
{
    use DisableForeignKeys;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        self::getTipoImposto();
        $connection = 'mercado';

        $this->disableForeignKeys($connection);

        DB::connection($connection)->table('tipo_imposto')->truncate();
        DB::connection($connection)->table('tipo_imposto')->insert(self::getTipoImposto());

        $this->enableForeignKeys($connection);
    }

    private function getTipoImposto()
    {
        $tipo_arquivos = config('config.impostos');
        $array = [];
        foreach ($tipo_arquivos as $nome => $k) {
            $array[] = [
                'id' => $k,
                'nome' => $nome,
                'descricao' => ''
            ];
        }

        return $array;
    }
}
