<?php

namespace Modules\Mercado\Database\Seeders;

use App\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateTipoArquivoSeed extends Seeder
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
        DB::connection($connection)->table('tipo_arquivo')->truncate();
        DB::connection($connection)->table('tipo_arquivo')->insert(self::getTipoArquivos());
        $this->enableForeignKeys($connection);

    }

    private function getTipoArquivos()
    {
        $tipo_arquivos = config('config.tipo_arquivo');
        $array = [];
        foreach ($tipo_arquivos as $descricao => $id) {
            $descricaoFormatada = strtoupper(str_replace('_', ' ', $descricao));

            $array[] = [
                'id' => $id,
                'descricao' => $descricaoFormatada
            ];
        }

        return $array;
    }
}
