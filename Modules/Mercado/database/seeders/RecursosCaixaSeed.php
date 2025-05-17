<?php

namespace Modules\Mercado\Database\Seeders;

use App\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecursosCaixaSeed extends Seeder
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

        DB::connection($connection)->table('recursos')->truncate();
        DB::connection($connection)->table('recursos')->insert(self::getRecursos());
        $this->enableForeignKeys($connection);
    }

    private static function getRecursos()
    {
        $recursos = [];
        $configRecursos = config('config.caixa.recursos');

        foreach ($configRecursos as $nome => $r) {
            $recursos[] = [
                'id' => $r['id'],
                'nome' => $nome,
                'descricao' => $r['descricao']
            ];
        }

        return $recursos;
    }
}
