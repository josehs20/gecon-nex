<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Traits\DisableForeignKeys;

class CreateTipoUsuariosSeed extends Seeder
{
    use DisableForeignKeys;

    public function run()
    {
        $connection = 'gecon';

        $this->disableForeignKeys($connection);

        DB::connection($connection)->table('tipo_usuarios')->truncate();

        DB::connection($connection)->table('tipo_usuarios')->insert(self::getTipoUsuarios());

        $this->enableForeignKeys($connection);
    }

    private static function getTipoUsuarios()
    {
        $tiposUsuarios = config('config.tipo_usuarios');
        $array = [];

        foreach ($tiposUsuarios as $nome => $dados) {
            $array[] = [
                'id' => $dados['id'],
                'perfil' => $nome,
                'descricao' => $dados['descricao']
            ];
        }

        return $array;
    }
}
