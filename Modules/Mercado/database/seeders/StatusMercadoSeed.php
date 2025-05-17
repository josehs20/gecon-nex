<?php

namespace Modules\Mercado\Database\Seeders;

use App\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusMercadoSeed extends Seeder
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
        DB::connection($connection)->table('status')->truncate();
        DB::connection($connection)->table('status')->insert(self::getStatus());
        $this->enableForeignKeys($connection);

    }

    private static function getStatus()
    {
        $status = [];
        $configStatus = config('config.status');

        foreach ($configStatus as $nome => $id) {
            // Substitui os sublinhados por espaços e capitaliza a primeira letra de cada palavra
            $descricaoFormatada = strtoupper(str_replace('_', ' ', $nome));

            $status[] = [
                'id' => $id,
                'descricao' => $descricaoFormatada
            ];
        }

        return $status;
    }
}
