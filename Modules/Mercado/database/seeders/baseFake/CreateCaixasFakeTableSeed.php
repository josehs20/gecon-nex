<?php

namespace Modules\Mercado\Database\Seeders\baseFake;

use App\Models\User;
use App\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Mercado\Entities\Caixa;
use Modules\Mercado\Entities\Recurso;
use Modules\Mercado\Entities\Usuario;

class CreateCaixasFakeTableSeed extends Seeder
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

        DB::connection($connection)->table('caixas')->truncate();

        DB::connection($connection)->table('caixas')->insert(self::getCaixas());
        $this->enableForeignKeys($connection);

        $this->createCaixaRecuros();
        $this->createCaixaPermissoes();
    }

    private static function getCaixas()
    {
        return  [
            [
                'nome' => 'CAIXA 1',
                'loja_id' => 1,
                'usuario_id' => null,
                'status_id' => config('config.status.fechado'),
                'ativo' => 1,
            ],
            [
                'nome' => 'CAIXA 2',
                'loja_id' => 1,
                'usuario_id' => null,
                'status_id' => config('config.status.fechado'),
                'ativo' => 1,
            ],
            [
                'nome' => 'CAIXA 1',
                'loja_id' => 2,
                'usuario_id' => null,
                'status_id' => config('config.status.fechado'),
                'ativo' => 1,
            ],
            [
                'nome' => 'CAIXA 2',
                'loja_id' => 2,
                'usuario_id' => null,
                'status_id' => config('config.status.fechado'),
                'ativo' => 1,
            ],
        ];
    }

    private function createCaixaRecuros()
    {
        $recursos = Recurso::get();
        $caixas = Caixa::get();
        $recursos_caixa = [];
        foreach ($caixas as $key => $c) {
            foreach ($recursos as $key => $r) {
                $recursos_caixa[] = [
                    // 'nome' => $r->nome,
                    'caixa_id' => $c->id,
                    'recurso_id' => $r->id,
                    // 'descricao' => $r->descricao,
                ];
            }
        }
        $connection = 'mercado';
        $this->disableForeignKeys($connection);
        DB::connection($connection)->table('caixa_recursos')->truncate();
        DB::connection($connection)->table('caixa_recursos')->insert($recursos_caixa);
        $this->enableForeignKeys($connection);
    }

    private function createCaixaPermissoes()
    {
        $usuarios = Usuario::whereIn(
            'usuario_master_cod',
            User::where('tipo_usuario_id', config('config.tipo_usuarios.cliente_master.id'))
                ->pluck('id')  // Aqui usamos pluck() para pegar apenas os IDs dos usuários
        )->get();

        $caixa_permissoes = [];

        foreach ($usuarios as $key => $u) {
            $caixas = Caixa::whereIn('loja_id', $u->lojas->pluck('id')->toArray())->get();
            foreach ($caixas as $key => $c) {
                $caixa_permissoes[] = [
                    'caixa_id' => $c->id,
                    'usuario_id' => $u->id,
                    'superior' => true,
                ];
            }
        }
        $connection = 'mercado';
        $this->disableForeignKeys($connection);

        DB::connection($connection)->table('caixa_permissoes')->truncate();

        DB::connection($connection)->table('caixa_permissoes')->insert($caixa_permissoes);
        $this->enableForeignKeys($connection);
    }
}
