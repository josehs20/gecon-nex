<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusGeconSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('status')->truncate();
        DB::table('status')->insert(self::getStatus());
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }

    private static function getStatus()
    {
        $status = [];
        $configStatus = config('config.status');

        foreach ($configStatus as $nome => $id) {
            $status[] = [
                'id' => $id,
                'descricao' => $nome
            ];
        }

        return $status;
    }
}
