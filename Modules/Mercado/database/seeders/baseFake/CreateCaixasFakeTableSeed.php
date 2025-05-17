<?php

namespace Modules\Mercado\Database\Seeders\baseFake;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Mercado\Entities\Caixa;
use Modules\Mercado\Entities\Recurso;
use Modules\Mercado\Entities\Usuario;

class CreateCaixasFakeTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::connection(config('database.connections.mercado.database'))->table('caixas')->truncate();

        DB::connection(config('database.connections.mercado.database'))->table('caixas')->insert(self::getCaixas());
        DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=1;');
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
        DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::connection(config('database.connections.mercado.database'))->table('caixa_recursos')->truncate();
        DB::connection(config('database.connections.mercado.database'))->table('caixa_recursos')->insert($recursos_caixa);
        DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=1;');
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

        DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::connection(config('database.connections.mercado.database'))->table('caixa_permissoes')->truncate();

        DB::connection(config('database.connections.mercado.database'))->table('caixa_permissoes')->insert($caixa_permissoes);
        DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
