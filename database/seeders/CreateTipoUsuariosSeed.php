<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateTipoUsuariosSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('tipo_usuarios')->truncate();
        DB::table('tipo_usuarios')->insert(self::getTipoUsuarios());
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }

    private static function getTipoUsuarios()
    {
        $tiposUsuarios = config('config.tipo_usuarios');
        $array = [];

        foreach ($tiposUsuarios as $nome => $dados) {
            $array[] = ['id' => $dados['id'], 'perfil' => $nome, 'descricao' => $dados['descricao']];
        }

        return $array;
    }
}
