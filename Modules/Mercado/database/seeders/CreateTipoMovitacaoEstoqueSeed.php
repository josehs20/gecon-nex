<?php

namespace Modules\Mercado\Database\Seeders;

use App\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateTipoMovitacaoEstoqueSeed extends Seeder
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
        DB::connection($connection)->table('tipo_movimentacao_estoque')->truncate();
        DB::connection($connection)->table('tipo_movimentacao_estoque')->insert(self::getStatus());
        $this->enableForeignKeys($connection);

    }

    private static function getStatus()
    {
        $status = [];
        $tipoMovimentacoes = config('config.tipo_movimentacao_estoque');

        foreach ($tipoMovimentacoes as $descricao => $id) {
            // Substitui os sublinhados por espaços e capitaliza a primeira letra de cada palavra
            $descricaoFormatada = strtoupper(str_replace('_', ' ', $descricao));

            $status[] = [
                'id' => $id,
                'descricao' => $descricaoFormatada
            ];
        }

        return $status;
    }
}
