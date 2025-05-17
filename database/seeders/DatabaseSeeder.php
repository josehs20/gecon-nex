<?php

namespace Database\Seeders;

use Database\Seeders\permissoes\ProcessosUsuariosSeed;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(CreateModulosSeed::class);
        $this->call(AcoesGeconSeed::class);
        $this->call(CreateTipoUsuariosSeed::class);
        $this->call(ProcessosUsuariosSeed::class);
        $this->call(StatusGeconSeed::class);
        $this->call(CreateUserAdminSeed::class);

    }
}
