<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Mercado\Entities\Usuario;

class CreateUserAdminSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::where('tipo_usuario_id', config('config.tipo_usuarios.admin.id'))->first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin',
                'login' => 'admin',
                'password' => bcrypt('secret'),
                'email' => 'admin@gecon.com.br',
                'modulo_id' => config('config.modulos.gecon'),
                'tipo_usuario_id' => config('config.tipo_usuarios.admin.id'),
            ]);

            Usuario::create([
                'usuario_master_cod' => $admin->id,
            ]);
        }
    }
}
