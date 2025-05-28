<?php

namespace Modules\Mercado\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    public function index($msg = null)
    {
        if ($msg != null) {
            session()->flash('error', $msg);
        }
        if (auth()->user()) {
            $categorias = [
                [
                    'nome' => 'Gerenciamento',
                    'submenus' => [
                        ['nome' => 'Usuários', 'icone' => 'bi bi-people', 'rota' => 'usuarios.index'],
                        ['nome' => 'Relatórios', 'icone' => 'bi bi-bar-chart', 'rota' => 'relatorios.index'],
                        ['nome' => 'Configurações', 'icone' => 'bi bi-gear', 'rota' => 'configuracoes.index'],
                        ['nome' => 'Permissões', 'icone' => 'bi bi-lock', 'rota' => 'permissoes.index'],
                    ]
                ],
                [
                    'nome' => 'Vendas',
                    'submenus' => [
                        ['nome' => 'Pedidos', 'icone' => 'bi bi-cart', 'rota' => 'pedidos.index'],
                        ['nome' => 'Clientes', 'icone' => 'bi bi-people-fill', 'rota' => 'clientes.index'],
                        ['nome' => 'Produtos', 'icone' => 'bi bi-box', 'rota' => 'produtos.index'],
                        ['nome' => 'Faturamento', 'icone' => 'bi bi-currency-dollar', 'rota' => 'faturamento.index'],
                    ]
                ],
                [
                    'nome' => 'Suporte',
                    'submenus' => [
                        ['nome' => 'Chamados', 'icone' => 'bi bi-headset', 'rota' => 'chamados.index'],
                        ['nome' => 'FAQ', 'icone' => 'bi bi-question-circle', 'rota' => 'faq.index'],
                    ]
                ],
                [
                    'nome' => 'Marketing',
                    'submenus' => [
                        ['nome' => 'Campanhas', 'icone' => 'bi bi-megaphone', 'rota' => 'campanhas.index'],
                        ['nome' => 'E-mails', 'icone' => 'bi bi-envelope', 'rota' => 'emails.index'],
                        ['nome' => 'Analytics', 'icone' => 'bi bi-graph-up', 'rota' => 'analytics.index'],
                    ]
                ]
            ];
            return view('mercado::master.index', compact('categorias'));
        } else {
            auth()->logout();
            return redirect('/login'); // Redireciona com mensagem

        }
    }
}
