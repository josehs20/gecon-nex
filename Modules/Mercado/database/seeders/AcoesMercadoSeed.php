<?php

namespace Modules\Mercado\Database\Seeders;

use App\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcoesMercadoSeed extends Seeder
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

        DB::connection($connection)->table('acoes')->truncate();

        DB::connection($connection)->table('acoes')->insert(self::getAcoes());

        $this->enableForeignKeys($connection);

    }

    private static function getAcoes()
    {
        return config('config.acoes');
    }
}
