<?php

namespace Database\Seeders\baseFake;

use App\Application\UsuarioApplication;
use App\Models\Empresa;
use App\Models\User;
use App\UseCases\Usuario\Requests\UsuarioRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Mercado\Entities\Endereco;
use Modules\Mercado\Entities\Usuario;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class UsuariosFakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipo_usuarios = [
            'cliente_master' => ['name' => 'Master Gecon', 'login' => 'Master.Gecon'],
            'gerente' => ['name' => 'Nick Fury', 'login' => 'nick.fury'],
            'caixa' => ['name' => 'Peter Parker', 'login' => 'peter.parker'],
            'estoquista' => ['name' => 'Steve Rogers', 'login' => 'steve.rogers'],
            'gerente_estoque' => ['name' => 'Natasha Romanoff', 'login' => 'natasha.romanoff'],
            'atendentes' => ['name' => 'Bruce Banner', 'login' => 'bruce.banner'],
        ];
        $empresas = Empresa::where('ativo', true)->get();
        $endereco = Endereco::first();
        $processo_id = config('config.processos.empresas.empresa.id');
        $acao_id = config('config.acoes.cadastrou_usuario.id');
        $usuarioAdmin = User::where('tipo_usuario_id', config('config.tipo_usuarios.admin.id'))->first();
        $usuario_id = $usuarioAdmin->id;
        $comentario = 'Criado pelo sistema.';
        foreach ($empresas as $key => $empresa) {
            foreach ($empresa->mercadoLojas as $key => $l) {
                foreach ($tipo_usuarios as $tipo => $value) {
                        $modulo_id = config('config.modulos.mercado');
                        $nome = 'usuario.' . $tipo . '_' . $l->id . '_' . $key;
                        $login = 'login.' . $nome;
                        $status_id = config('config.status.ativo');
                        $historicoRequest = new CriarHistoricoRequest($processo_id, $acao_id, $usuario_id, $comentario);
                        $user = UsuarioApplication::criar(
                            new UsuarioRequest(
                                $nome,
                                $login,
                                $nome . '@gecon.com.br',
                                'secret',
                                $modulo_id,
                                true,
                                config('config.tipo_usuarios.'.$tipo.'.id'),
                                $empresa->id,
                                $empresa->mercadoLojas->pluck('id')->toArray(),
                                $endereco->id,
                                $status_id,
                                null,
                                self::gerarCpfAleatorio(),
                                null,
                                null,
                                true,
                                null,
                                0,
                                null,
                                null,
                                0,
                                $historicoRequest
                            )
                        );
                }
            }
        }

        // foreach (config('config.tipo_usuarios') as $tipo => $dadosTipo) {
        //     // Verifica se o item é um array ou um valor direto
        //     $idTipo = is_array($dadosTipo) ? $dadosTipo['id'] : $dadosTipo;

        //     // Ignorar 'admin' e 'cliente_master'
        //     if (config('config.tipo_usuarios.admin.id') != $idTipo && config('config.tipo_usuarios.cliente_master.id') != $idTipo) {
        //         $user = User::create([
        //             'name' => $usuarios[$tipo]['name'],
        //             'login' => $usuarios[$tipo]['login'],
        //             'email' => $usuarios[$tipo]['login'] . '@gecon.com',
        //             'permite_abrir_caixa' => $tipo === 'caixa' ? 1 : 0,
        //             'tipo_usuario_id' => $idTipo,
        //             'empresa_id' => 1,
        //             'modulo_id' => 2,
        //             'password' => Hash::make('secret')
        //         ]);

        //         Usuario::create([
        //             'usuario_master_cod' => $user->id,
        //             'loja_id' => 1,
        //             'endereco_id' => 1,
        //             'status_id' => 1,
        //             'ativo' => 1,
        //         ]);
        //     }
        // }
    }
    private static function gerarCpfAleatorio(): string
    {
        $n1 = rand(0, 9);
        $n2 = rand(0, 9);
        $n3 = rand(0, 9);
        $n4 = rand(0, 9);
        $n5 = rand(0, 9);
        $n6 = rand(0, 9);
        $n7 = rand(0, 9);
        $n8 = rand(0, 9);
        $n9 = rand(0, 9);

        $d1 = $n9 * 2 + $n8 * 3 + $n7 * 4 + $n6 * 5 + $n5 * 6 + $n4 * 7 + $n3 * 8 + $n2 * 9 + $n1 * 10;
        $d1 = 11 - ($d1 % 11);
        $d1 = ($d1 >= 10) ? 0 : $d1;

        $d2 = $d1 * 2 + $n9 * 3 + $n8 * 4 + $n7 * 5 + $n6 * 6 + $n5 * 7 + $n4 * 8 + $n3 * 9 + $n2 * 10 + $n1 * 11;
        $d2 = 11 - ($d2 % 11);
        $d2 = ($d2 >= 10) ? 0 : $d2;

        return "$n1$n2$n3$n4$n5$n6$n7$n8$n9$d1$d2";
    }
}
