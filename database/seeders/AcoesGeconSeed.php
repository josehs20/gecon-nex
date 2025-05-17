<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Traits\DisableForeignKeys;

class AcoesGeconSeed extends Seeder
{
    use DisableForeignKeys;

    public function run()
    {
        $connection = 'gecon';

        $this->disableForeignKeys($connection);

        DB::connection($connection)->table('acoes')->truncate();
        DB::connection($connection)->table('acoes')->insert(self::getAcoes());

        $this->enableForeignKeys($connection);
    }

    private static function getAcoes()
    {
        return config('config.acoes');
    }
}
