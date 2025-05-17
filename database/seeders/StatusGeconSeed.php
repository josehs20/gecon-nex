<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Traits\DisableForeignKeys;

class StatusGeconSeed extends Seeder
{
    use DisableForeignKeys;

    public function run()
    {
        $connection = 'gecon';

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
            $status[] = [
                'id' => $id,
                'descricao' => $nome,
            ];
        }

        return $status;
    }
}
