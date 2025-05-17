<?php

namespace Database\Seeders\permissoes;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProcessosUsuariosSeed extends Seeder
{
    /**
     * PROCESSOS DE PERMISSAO PARA OS USUARIOS E TIPOS DE USUÁRIO DE CADA MODULO
     */
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        self::truncaTabelas();
        self::insertProcessos();
        self::insertProcessosUsuarios();
        // DB::connection(config('database.connections.mercado.database'))->table('tipo_usuarios')->insert(self::getTipoUsuarios());
    }

    private function truncaTabelas()
    {
        DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::connection(config('database.connections.mercado.database'))->table('processos')->truncate();
        
        DB::connection(config('database.connections.mercado.database'))->table('processo_tipo_usuario')->truncate();

        DB::connection(config('database.connections.mercado.database'))->statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('processos')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function insertProcessosUsuarios()
    {
        $arrayPermissoes = self::getPermissaoProcessoUsuario();
        DB::connection(config('database.connections.mercado.database'))->table('processo_tipo_usuario')->insert($arrayPermissoes);
    }

    private static function insertProcessos(): void
    {
        $processos = [];
        foreach (config('config.processos') as $key => $p) {
            foreach ($p as $key => $item) {
                if (is_array($item)) {
                    $processos[] = $item;
                } elseif($key != 'nome') {
                    $processos[] = $p;
                    break;
                }
            }
        }
        
        DB::connection(config('database.connections.mercado.database'))->table('processos')->insert($processos);
        DB::table('processos')->insert($processos);
    }

    private static function getPermissaoProcessoUsuario()
    {
        return array_merge(self::getPermissaoAdmin(), self::getPermissaoMaster());
    }

    //-------------------Permissoes---------------//

    //Admin
    private static function getPermissaoAdmin()
    {
        $tipo_usuario = config('config.tipo_usuarios.admin.id');

        return [
            [
                'processo_id' => config('config.processos.empresas.empresa.id'),
                'tipo_usuario_id' => $tipo_usuario
            ],
            [
                'processo_id' => config('config.processos.empresas.gtin.id'),
                'tipo_usuario_id' => $tipo_usuario
            ],
            // [
            //     'processo_id' => config('config.processos.nfe.empresas.id'),
            //     'tipo_usuario_id' => $tipo_usuario
            // ],
            [
                'processo_id' => config('config.processos.gerenciamento.usuarios.id'),
                'tipo_usuario_id' => $tipo_usuario
            ],
            [
                'processo_id' => config('config.processos.gerenciamento.permissao_usuario.id'),
                'tipo_usuario_id' => $tipo_usuario
            ],
            [
                'processo_id' => config('config.processos.dashboard.id'),
                'tipo_usuario_id' => $tipo_usuario
            ],
        ];
    }

    //Master
    private static function getPermissaoMaster()
    {
        $tipo_usuario = config('config.tipo_usuarios.cliente_master.id');
        return [
            [
                'processo_id' => config('config.processos.gerenciamento.produto.id'),
                'tipo_usuario_id' => $tipo_usuario
            ],
            [
                'processo_id' => config('config.processos.gerenciamento.estoque.id'),
                'tipo_usuario_id' => $tipo_usuario
            ],
            [
                'processo_id' => config('config.processos.gerenciamento.fornecedor.id'),
                'tipo_usuario_id' => $tipo_usuario
            ],
            [
                'processo_id' => config('config.processos.gerenciamento.unidade_medida.id'),
                'tipo_usuario_id' => $tipo_usuario
            ],
            [
                'processo_id' => config('config.processos.gerenciamento.classificacao_produto.id'),
                'tipo_usuario_id' => $tipo_usuario
            ],
            [
                'processo_id' => config('config.processos.pdv.caixa.id'),
                'tipo_usuario_id' => $tipo_usuario
            ],
            [
                'processo_id' => config('config.processos.gerenciamento.caixas.id'),
                'tipo_usuario_id' => $tipo_usuario
            ],
            [
                'processo_id' => config('config.processos.gerenciamento.cliente.id'),
                'tipo_usuario_id' => $tipo_usuario
            ],
            [
                'processo_id' => config('config.processos.gerenciamento.balanco.id'),
                'tipo_usuario_id' => $tipo_usuario
            ],
            [
                'processo_id' => config('config.processos.gerenciamento.movimentacao.id'),
                'tipo_usuario_id' => $tipo_usuario
            ],
            [
                'processo_id' => config('config.processos.gerenciamento.forma_pagemento.id'),
                'tipo_usuario_id' => $tipo_usuario
            ],
            [
                'processo_id' => config('config.processos.pdv.fechamento_caixa.id'),
                'tipo_usuario_id' => $tipo_usuario
            ],
            [
                'processo_id' => config('config.processos.gerenciamento.usuarios.id'),
                'tipo_usuario_id' => $tipo_usuario
            ],
            [
                'processo_id' => config('config.processos.gerenciamento.permissao_usuario.id'),
                'tipo_usuario_id' => $tipo_usuario
            ],
            [
                'processo_id' => config('config.processos.gerenciamento.recebimento.id'),
                'tipo_usuario_id' => $tipo_usuario
            ],
            [
                'processo_id' => config('config.processos.gerenciamento.pedidos.id'),
                'tipo_usuario_id' => $tipo_usuario
            ],
            [
                'processo_id' => config('config.processos.gerenciamento.recebimento_pedido.id'),
                'tipo_usuario_id' => $tipo_usuario
            ],
            [
                'processo_id' => config('config.processos.nfe.empresa.id'),
                'tipo_usuario_id' => $tipo_usuario
            ],
            [
                'processo_id' => config('config.processos.nfe.certificado.id'),
                'tipo_usuario_id' => $tipo_usuario
            ],
            [
                'processo_id' => config('config.processos.nfe.inscricao_estadual.id'),
                'tipo_usuario_id' => $tipo_usuario
            ],
            [

                'processo_id' => config('config.processos.gerenciamento.cotacao.id'),
                'tipo_usuario_id' => $tipo_usuario
            ],
            [

                'processo_id' => config('config.processos.gerenciamento.fabricantes.id'),
                'tipo_usuario_id' => $tipo_usuario
            ],
            [

                'processo_id' => config('config.processos.gerenciamento.compras.id'),
                'tipo_usuario_id' => $tipo_usuario
            ],
            [
                'processo_id' => config('config.processos.dashboard.id'),
                'tipo_usuario_id' => $tipo_usuario
            ],
        ];
    }
}
