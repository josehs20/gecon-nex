<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Mercado\Entities\ProcessoUsuario;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //MANIPULAÇAO DE ONDE VAI CADA USUARIO QUE LOGAR
        $rotaRedirect = false;
        self::carregaSessionMenu();
        
        if (auth()->user() && auth()->user()->isAdmin()) {
            $rotaRedirect = redirect()->route('admin.empresa.index');
        } elseif (auth()->user()->isUsuarioGecon()) {
            $rotaRedirect = redirect()->route('home.index');
        }
        // dd($rotaRedirect, auth()->user()->isUsuarioGecon());
        if ($rotaRedirect === false) {
            auth()->logout();
            return redirect()->route('login');
        } else {
            self::carregaSessionMenu();
            session()->flash('limpaStorage', true);
            return $rotaRedirect;
        }
    }

    public function carregaSessionMenu()
    {
        $processoUsuario = ProcessoUsuario::where('tipo_usuario_id', auth()->user()->tipo_usuario_id)->get();
        $arrayMenuFomaratado = self::montaArrayMenu($processoUsuario);

        session(['processoUsuario' => $processoUsuario->pluck('processo_id')->toArray()]);
        session(['menu' => $arrayMenuFomaratado]);
    }

    public function montaArrayMenu($processoUsuario)
    {
        $menusPrincipal = config('config.processos');
        $array = [];
        $posicoesMenus = self::getPosicoesMenu();

        foreach ($menusPrincipal as $grupo => $submenu) {
            $contem_submenus = collect($submenu)->contains(function($sub){
                return is_array($sub);
            });

            if($contem_submenus){
                foreach ($submenu as $key => $processo) {
                    if (is_array($processo) && isset($processo['id'])) {
                        // Verifica permissão do usuário
                        $temPermissao = $processoUsuario->where('processo_id', $processo['id'])->first();

                        if ($temPermissao) {
                            foreach ($posicoesMenus as $keyPos => $value) {
                                if (in_array($temPermissao->processo_id, $value)) {
                                    // Adiciona o submenu no grupo correspondente
                                    $array[$grupo]['nome'] = $submenu['nome'];
                                    $array[$grupo]['subMenus'][$keyPos][] = $temPermissao->processo;
                                }
                            }
                        }
                    }
                }
            } else {
                // Verifica permissão do usuário
                $temPermissao = $processoUsuario->where('processo_id', $submenu['id'])->first();

                if ($temPermissao) {
                    foreach ($posicoesMenus as $keyPos => $value) {
                        if (in_array($temPermissao->processo_id, $value)) {
                            // Adiciona o submenu no grupo correspondente
                            $array[$grupo]['nome'] = $submenu['nome'];
                            $array[$grupo]['subMenus'] = $temPermissao->processo;
                        }
                    }
                }
            }

        }

        // Ordena os subMenus de cada grupo por chave alfabética
        foreach ($array as &$grupo) {
            $contem_submenus = collect($grupo)->contains(function($sub){
                return is_array($sub);
            });

            if($contem_submenus){
                // Ordena a chave (subMenus)
                ksort($grupo['subMenus']);  // Ordena as chaves dos subMenus em ordem alfabética (ascendente)

                // Ordena os valores (submenus) de cada chave pela propriedade 'nome'
                foreach ($grupo['subMenus'] as &$submenu) {
                    usort($submenu, function ($a, $b) {
                        return strcmp($a['nome'], $b['nome']);  // Ordena submenus por nome em ordem alfabética
                    });
                }
            }
        }

        return $array;
    }


    public function validateCaixa($temPermissao)
    {

        if (!$temPermissao || $temPermissao->processo_id == config('config.processos.pdv.caixa.id') && !auth()->user()->isCaixa()) {

            return null;
        } else {
            return $temPermissao;
        }
    }

    public function getPosicoesMenu()
    {
        return [
            'Dashboard' => [
                config('config.processos.dashboard.id')
            ],
            'Admin' => [
                config('config.processos.empresas.empresa.id'),
                config('config.processos.empresas.gtin.id'),
            ],
            'Cadastros' => [
                config('config.processos.gerenciamento.produto.id'),
                config('config.processos.gerenciamento.unidade_medida.id'),
                config('config.processos.gerenciamento.classificacao_produto.id'),
                config('config.processos.gerenciamento.fornecedor.id'),
                config('config.processos.gerenciamento.caixas.id'),
                config('config.processos.gerenciamento.cliente.id'),
                config('config.processos.gerenciamento.forma_pagemento.id'),
                config('config.processos.gerenciamento.fabricantes.id'),
                config('config.processos.gerenciamento.usuarios.id'),
                config('config.processos.gerenciamento.permissao_usuario.id'),
            ],
            'Estoque' => [
                config('config.processos.gerenciamento.estoque.id'),
                config('config.processos.gerenciamento.balanco.id'),
                config('config.processos.gerenciamento.movimentacao.id'),
                config('config.processos.gerenciamento.recebimento.id'),
            ],
            'Pedidos' => [
                config('config.processos.gerenciamento.pedidos.id'),
                config('config.processos.gerenciamento.cotacao.id'),
                config('config.processos.gerenciamento.compras.id'),
                config('config.processos.gerenciamento.recebimento_pedido.id'),

            ],
            'PDV' => [
                config('config.processos.pdv.caixa.id'),
                config('config.processos.pdv.fechamento_caixa.id'),
            ],
            'NFE' => [
                config('config.processos.nfe.empresa.id'),
                config('config.processos.nfe.certificado.id'),
                config('config.processos.nfe.inscricao_estadual.id'),
            ],
        ];
    }
}
