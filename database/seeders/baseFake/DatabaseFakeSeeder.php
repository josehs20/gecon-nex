<?php

namespace Database\Seeders\baseFake;

use Database\Seeders\StatusGeconSeed;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseFakeSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call(CreateEmpresasDesenvolvimentoSeed::class);
        $this->call(CreateLojaFakeSeed::class);
    }
}
