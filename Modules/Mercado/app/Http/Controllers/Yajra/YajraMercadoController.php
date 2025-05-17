<?php

namespace Modules\Mercado\Http\Controllers\Yajra;

use App\Helpers\YajraQueryBuilder;
use App\System\Post;
use Illuminate\Http\Request;
use Modules\Mercado\Entities\Balanco;
use Modules\Mercado\Entities\BalancoItem;
use Modules\Mercado\Entities\Caixa;
use Modules\Mercado\Entities\ClassificacaoProduto;
use Modules\Mercado\Entities\Cliente;
use Modules\Mercado\Entities\Compra;
use Modules\Mercado\Entities\Cotacao;
use Modules\Mercado\Entities\Estoque;
use Modules\Mercado\Entities\Fabricante;
use Modules\Mercado\Entities\FormaPagamento;
use Modules\Mercado\Entities\Fornecedor;
use Modules\Mercado\Entities\MovimentacaoEstoque;
use Modules\Mercado\Entities\MovimentacaoEstoqueItem;
use Modules\Mercado\Entities\Pedido;
use Modules\Mercado\Entities\QueryBuilderMercado;
use Modules\Mercado\Entities\UnidadeMedida;
use Modules\Mercado\Repository\MovimentacaoEstoque\MovimentacaoEstoqueRepository;
use Yajra\DataTables\Facades\DataTables;

class YajraMercadoController
{
    public function getCaixas(Request $request)
    {
        $parans = Post::anti_injection_yajra($request->all());

        $query = new YajraQueryBuilder(Caixa::query());
        return $query->limit(100)->where('loja_id', auth()->user()->getUserModulo->loja_id)
            ->reject(['ativo'])->whereYQB($parans->getAttributes())
            ->specifyColumnYQB('ativo', function ($q, $valor) {
                return $q->where('ativo', !str_contains('ina', $valor));
            })->constructColumns(function ($dataTables) {
                return $dataTables->addColumn('id', function ($caixa) {
                    return $caixa->id;
                })
                    ->addColumn('nome', function ($caixa) {
                        return strtoupper($caixa->nome); // Exemplo: Nome em maiúsculas
                    })
                    ->addColumn('loja_id', function ($caixa) {

                        return $caixa->loja->nome;
                    })
                    ->addColumn('ativo', function ($caixa) {
                        // Verificar se a empresa está ativa ou inativa e retornar o HTML com o badge
                        $status = $caixa->ativo ? 'Ativo' : 'Inativo';
                        $badgeClass = $caixa->ativo ? 'badge bg-primary' : 'badge bg-danger';  // 'bg-primary' para azul, 'bg-danger' para vermelho
                        return "<span class='$badgeClass'>$status</span>";
                    })
                    ->addColumn('acao', function ($caixa) {
                        // Gerando o HTML do botão de edição
                        return '<a href="' . route('cadastro.caixa.edit', ['id' => $caixa->id]) . '" class="btn btn-warning">
                    <i class="bi bi-pencil"></i>
                </a>';
                    });
            });
    }

    public function getClassificacaoProduto(Request $request)
    {
        $parans = Post::anti_injection_yajra($request->all());

        $query = new YajraQueryBuilder(ClassificacaoProduto::query());
        return $query->limit(100)->where('empresa_master_cod', auth()->user()->empresa_id, '=')
            ->whereYQB($parans->getAttributes())->constructColumns(function ($dataTables) {
                return $dataTables->addColumn('id', function ($classificacaoProduto) {
                    return $classificacaoProduto->id;
                })
                    ->addColumn('nome', function ($classificacaoProduto) {
                        return strtoupper($classificacaoProduto->descricao); // Exemplo: Nome em maiúsculas
                    })
                    ->addColumn('acao', function ($classificacaoProduto) {
                        // Gerando o HTML do botão de edição
                        return '<a href="' . route('cadastro.classificacao_produto.edit', ['id' => $classificacaoProduto->id]) . '" class="btn btn-warning">
                <i class="bi bi-pencil"></i>
            </a>';
                    });
            });
    }

    public function getClientes(Request $request)
    {
        $parans = Post::anti_injection_yajra($request->all());

        $query = new YajraQueryBuilder(Cliente::query());
        return $query->limit(100)->where('documento', '!=', 00000000000)
            ->where('empresa_master_cod', auth()->user()->empresa_id)
            ->reject(['ativo'])->whereYQB($parans->getAttributes())
            ->specifyColumnYQB('ativo', function ($q, $valor) {
                return $q->where('ativo', !str_contains('ina', $valor));
            })->constructColumns(function ($dataTables) {
                return $dataTables->addColumn('id', function ($cliente) {
                    return $cliente->id;
                })
                    ->addColumn('nome', function ($cliente) {
                        return strtoupper($cliente->nome); // Exemplo: Nome em maiúsculas
                    })
                    ->addColumn('documento', function ($cliente) {
                        return $cliente->documento; // Exemplo: Nome em maiúsculas
                    })
                    ->addColumn('status.descricao', function ($cliente) {
                        $status = $cliente->status->descricao();
                        $badgeClass = $cliente->status->badge(); // 'bg-primary' para azul, 'bg-danger' para vermelho
                        return "<span class='$badgeClass'>$status</span>";
                    })
                    ->addColumn('ativo', function ($caixa) {
                        // Verificar se a empresa está ativa ou inativa e retornar o HTML com o badge
                        $status = $caixa->ativo ? 'Ativo' : 'Inativo';
                        $badgeClass = $caixa->ativo ? 'badge bg-primary' : 'badge bg-danger';  // 'bg-primary' para azul, 'bg-danger' para vermelho
                        return "<span class='$badgeClass'>$status</span>";
                    })
                    ->addColumn('celular', function ($cliente) {
                        return $cliente->celular; // Exemplo: Nome em maiúsculas
                    })
                    ->addColumn('email', function ($cliente) {
                        return $cliente->email; // Exemplo: Nome em maiúsculas
                    })
                    ->addColumn('acao', function ($cliente) {
                        return '
                    <div class="d-flex gap-1">
                        <a href="' . route('cadastro.cliente.edit', ['id' => $cliente->id]) . '" class="btn btn-warning btn-sm mx-1" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <button class="btn btn-info btn-sm mostrarDadosCliente" data-id="' . $cliente->id . '" title="Visualizar">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                ';
                    });
            });
    }

    public function getFormasPagamento(Request $request)
    {
        $parans = Post::anti_injection_yajra($request->all());
        $query = new YajraQueryBuilder(FormaPagamento::query());
        return $query->limit(100)->where('loja_id', auth()->user()->getUserModulo->loja_id)
            ->reject(['ativo'])->whereYQB($parans->getAttributes())
            ->specifyColumnYQB('ativo', function ($q, $valor) {
                return $q->where('ativo', !str_contains('ina', $valor));
            })->constructColumns(function ($dataTables) {
                return $dataTables->addColumn('id', function ($formaPagamento) {
                    return $formaPagamento->id;
                })
                    ->addColumn('descricao', function ($formaPagamento) {
                        return strtoupper($formaPagamento->descricao); // Exemplo: Nome em maiúsculas
                    })
                    ->addColumn('loja.nome', function ($formaPagamento) {
                        return $formaPagamento->loja->nome; // Exemplo: Nome em maiúsculas
                    })->addColumn('descricao', function ($formaPagamento) {
                        return $formaPagamento->descricao; // Exemplo: Nome em maiúsculas
                    })
                    ->addColumn('ativo', function ($formaPagamento) {
                        // Verificar se a empresa está ativa ou inativa e retornar o HTML com o badge
                        $status = $formaPagamento->ativo ? 'Ativo' : 'Inativo';
                        $badgeClass = $formaPagamento->ativo ? 'badge bg-primary' : 'badge bg-danger';  // 'bg-primary' para azul, 'bg-danger' para vermelho
                        return "<span class='$badgeClass'>$status</span>";
                    });
            });
    }

    public function getFornecedores(Request $request)
    {
        $parans = Post::anti_injection_yajra($request->all());
        $query = new YajraQueryBuilder(Fornecedor::query());
        return $query->limit(100)->where('empresa_master_cod', auth()->user()->empresa_id)
            ->reject(['ativo'])->whereYQB($parans->getAttributes())
            ->specifyColumnYQB('ativo', function ($q, $valor) {
                return $q->where('ativo', !str_contains('ina', $valor));
            })->constructColumns(function ($dataTables) {
                return $dataTables->addColumn('id', function ($fornecedor) {
                    return $fornecedor->id;
                })
                    ->addColumn('descnome_fantasiaricao', function ($fornecedor) {
                        return strtoupper($fornecedor->nome_fantasia);
                    })
                    ->addColumn('documento', function ($fornecedor) {
                        return $fornecedor->documento;
                    })
                    ->addColumn('celular', function ($fornecedor) {
                        return $fornecedor->celular;
                    })
                    ->addColumn('email', function ($fornecedor) {
                        return $fornecedor->email;
                    })
                    ->addColumn('ativo', function ($fornecedor) {
                        // Verificar se a empresa está ativa ou inativa e retornar o HTML com o badge
                        $status = $fornecedor->ativo ? 'Ativo' : 'Inativo';
                        $badgeClass = $fornecedor->ativo ? 'badge bg-primary' : 'badge bg-danger';  // 'bg-primary' para azul, 'bg-danger' para vermelho
                        return "<span class='$badgeClass'>$status</span>";
                    })
                    ->addColumn('acao', function ($fornecedor) {
                        return '
                    <div class="d-flex gap-1">
                        <a href="' . route('cadastro.fornecedor.edit', ['id' => $fornecedor->id]) . '" class="btn btn-warning btn-sm mx-1" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <button class="btn btn-info btn-sm mostrarDadosFornecedor" data-id="' . $fornecedor->id . '" title="Visualizar">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                ';
                    });
            });
    }

    public function getUnidadeMedidas(Request $request)
    {
        $parans = Post::anti_injection_yajra($request->all());

        $query = new YajraQueryBuilder(UnidadeMedida::query());
        return $query->limit(100)->where('empresa_master_cod', auth()->user()->empresa_id)
            ->reject(['pode_ser_float'])->whereYQB($parans->getAttributes())
            ->specifyColumnYQB('pode_ser_float', function ($q, $valor) {
                return $q->where('pode_ser_float', str_contains('sim', $valor));
            })->constructColumns(function ($dataTables) {
                return $dataTables->addColumn('id', function ($unidadeMedida) {
                    return $unidadeMedida->id;
                })
                    ->addColumn('descricao', function ($unidadeMedida) {
                        return $unidadeMedida->descricao;
                    })
                    ->addColumn('silga', function ($unidadeMedida) {
                        return $unidadeMedida->sigla;
                    })
                    ->addColumn('pode_ser_float', function ($unidadeMedida) {
                        // Verificar se a empresa está ativa ou inativa e retornar o HTML com o badge
                        $status = $unidadeMedida->pode_ser_float ? 'Sim' : 'Não';
                        $badgeClass = $unidadeMedida->pode_ser_float ? 'badge bg-primary' : 'badge bg-danger';  // 'bg-primary' para azul, 'bg-danger' para vermelho
                        return "<span class='$badgeClass'>$status</span>";
                    })
                    ->addColumn('acao', function ($unidadeMedida) {
                        return '<a href="' . route('cadastro.unidade_medida.edit', ['id' => $unidadeMedida->id]) . '" class="btn btn-warning">
                <i class="bi bi-pencil"></i>
            </a>';
                    });
            });
    }

    /**
     * Balacos
     */
    public function getBalancos(Request $request)
    {
        $usuario_id = auth()->user()->getUserModulo->id;
        $parans = Post::anti_injection_yajra($request->all());

        $query = new YajraQueryBuilder(Balanco::query());

        return $query->limit(50)->where('usuario_id', $usuario_id)
            ->reject(['usuario_id', 'qtd_itens'])->whereYQB($parans->getAttributes())
            ->specifyColumnYQB('usuario_id', function ($q, $valor) use ($usuario_id) {
                return $q->where('usuario_id', $usuario_id);
            })->specifyColumnYQB('qtd_itens', function ($q, $valor) {
                return $q->withCount('balanco_itens') // Adiciona a contagem
                    ->having('balanco_itens_count', 'like', formataLikeSql($valor));
            })->constructColumns(function ($dataTables) {
                return $dataTables->addColumn('id', function ($balanco) {
                    return $balanco->id;
                })
                    ->addColumn('usuario_id', function ($balanco) {
                        return $balanco->usuario->master->name;
                    })
                    ->addColumn('status.descricao', function ($balanco) {
                        $status = $balanco->status->descricao();
                        $badgeClass = $balanco->status->badge(); // 'bg-primary' para azul, 'bg-danger' para vermelho

                        return "<span class='$badgeClass'>$status</span>";
                    })->addColumn('qtd_itens', function ($balanco) {
                        return $balanco->balanco_itens->count();
                    })
                    ->addColumn('created_at', function ($balanco) {
                        return $balanco->created_at->format('d-m-Y');
                    })->addColumn('observacao', function ($balanco) {
                        return $balanco->observacao;
                    })
                    ->addColumn('acao', function ($balanco) {
                        // Gerando o HTML do botão de edição
                        return '<a href="' . route('estoque.balanco.edit', ['id' => $balanco->id]) . '" class="btn btn-warning">
                        <i class="bi bi-pencil"></i>
                    </a>';
                    });
            });
    }

    public function getBalancosItens(Request $request)
    {
        $parans = Post::anti_injection_yajra($request->all());
        $dataAjax = (object) Post::anti_injection_array($request->dataAjax);

        $query = new QueryBuilderMercado(new BalancoItem());
        $query = $query->limit(50)->where('balanco_id', $dataAjax->balanco_id, '=')->construirQueryYajra($parans)->getQuery();
        $balanco = Balanco::find($dataAjax->balanco_id);

        $datatables = DataTables::of($query)
            ->addColumn('id', function ($balancoItem) {
                return $balancoItem->id;
            })
            ->addColumn('estoque@produto@nome', function ($balancoItem) {
                return $balancoItem->estoque->produto->getNomeCompleto();
            })
            ->addColumn('estoque@produto@fabricante@nome', function ($balancoItem) {
                return $balancoItem->estoque->produto->fabricante->nome;
            })
            ->addColumn('quantidade_estoque_sistema', function ($balancoItem) {
                return $balancoItem->quantidade_estoque_sistema;
            })
            ->addColumn('quantidade_estoque_real', function ($balancoItem) {
                return $balancoItem->quantidade_estoque_real;
            })
            ->addColumn('quantidade_resultado_operacional', function ($balancoItem) {
                return $balancoItem->quantidade_resultado_operacional;
            });
        // Informa ao DataTables que a coluna 'acao' e 'ativo' contêm HTML
        if ($balanco->status_id !=  config('config.status.concluido')) {
            $datatables->addColumn('acao', function ($balancoItem) {
                // Gerando o HTML do botão de edição
                return '<a href="#" class="btn btn-danger" style="color: #fff !important"
                                    onclick="confirmDelete(' . $balancoItem->id . ')">
                                    <i class="bi bi-trash"></i>
                                </a>';
            })
                ->rawColumns(['acao']);
        }

        return $datatables->make(true);
    }

    public function getEstoques(Request $request)
    {
        $parans = Post::anti_injection_yajra($request->all());

        $query = new QueryBuilderMercado(new Estoque());
        $query = $query->limit(100)->where('loja_id', auth()->user()->getUserModulo->loja_id, '=')->construirQueryYajra($parans)->getQuery();


        return DataTables::of($query)
            ->addColumn('id', function ($estoque) {
                return $estoque->id;
            })
            ->addColumn('produto@nome', function ($estoque) {
                return $estoque->produto->nome;
            })
            ->addColumn('produto@fabricante@nome', function ($estoque) {
                return $estoque->produto->fabricante->nome;
            })
            ->addColumn('quantidade_total', function ($estoque) {
                return $estoque->quantidade_total;
            })
            ->addColumn('quantidade_disponivel', function ($estoque) {
                return $estoque->quantidade_disponivel;
            })
            ->addColumn('quantidade_minima', function ($estoque) {
                return $estoque->quantidade_minima;
            })
            ->addColumn('quantidade_maxima', function ($estoque) {
                return $estoque->quantidade_maxima;
            })
            ->addColumn('localizacao', function ($estoque) {
                return $estoque->localizacao ?? '-';
            })
            ->addColumn('acao', function ($estoque) {
                // Gerando o HTML do botão de edição
                return '<a href="' . route('cadastro.estoque.edit', ['id' => $estoque->id]) . '" class="btn btn-warning">
                    <i class="bi bi-pencil"></i>
                </a>';
            })
            ->rawColumns(['acao'])  // Informa ao DataTables que a coluna 'acao' e 'ativo' contêm HTML
            ->make(true);
    }

    public function getMovimentacoes(Request $request)
    {
        $usuario = auth()->user()->getUserModulo;
        $parans = Post::anti_injection_yajra($request->all());
        $usuario_id = $usuario->id;
        $query = new YajraQueryBuilder(MovimentacaoEstoque::query());
        return $query->limit(100)->where('usuario_id', $usuario->id)
            ->where('loja_id', $usuario->loja_id, '=')->whereIn('tipo_movimentacao_estoque_id', [
                config('config.tipo_movimentacao_estoque.movimentacao')
            ])->reject(['usuario_id', 'qtd_itens'])->whereYQB($parans->getAttributes())
            ->specifyColumnYQB('usuario_id', function ($q, $valor) use ($usuario_id) {
                return $q->where('usuario_id', $usuario_id);
            })->specifyColumnYQB('qtd_itens', function ($q, $valor) {
                return $q->withCount('balanco_itens') // Adiciona a contagem
                    ->having('balanco_itens_count', 'like', formataLikeSql($valor));
            })->specifyColumnYQB('qtd_itens', function ($q, $valor) {
                return $q->withCount('movimentacao_estoque_itens') // Adiciona a contagem
                    ->having('movimentacao_estoque_itens_count', 'like', formataLikeSql($valor));
            })->constructColumns(function ($dataTables) {
                return $dataTables->addColumn('id', function ($movimentacao) {
                    return $movimentacao->id;
                })
                    ->addColumn('usuario_id', function ($movimentacao) {
                        return $movimentacao->usuario->master->name;
                    })
                    ->addColumn('status.descricao', function ($movimentacao) {
                        $status = $movimentacao->status->descricao();
                        $badgeClass = $movimentacao->status->badge(); // 'bg-primary' para azul, 'bg-danger' para vermelho
                        return "<span class='$badgeClass'>$status</span>";
                    })
                    ->addColumn('qtd_itens', function ($movimentacao) {
                        return $movimentacao->movimentacao_estoque_itens->count();
                    })
                    ->addColumn('created_at', function ($movimentacao) {
                        return $movimentacao->created_at->format('d-m-Y');
                    })->addColumn('observacao', function ($movimentacao) {
                        return $movimentacao->observacao;
                    })
                    ->addColumn('acao', function ($movimentacao) {
                        // Gerando o HTML do botão de edição
                        return '<a href="' . route('estoque.movimentacao.edit', ['id' => $movimentacao->id]) . '" class="btn btn-warning">
                    <i class="bi bi-pencil"></i>
                </a>';
                    });
            });
    }

    public function getMovimentacoesItens(Request $request)
    {
        $parans = Post::anti_injection_yajra($request->all());
        $dataAjax = $request->dataAjax;
        $query = new QueryBuilderMercado(new MovimentacaoEstoqueItem());
        $query = $query->limit(100)->where('movimentacao_id', $dataAjax['movimentacao_id'], '=')
            ->whereIn('tipo_movimentacao_estoque_id', [
                config('config.tipo_movimentacao_estoque.saida'),
                config('config.tipo_movimentacao_estoque.entrada')
            ])->construirQueryYajra($parans)->getQuery();

        $movimentacao = MovimentacaoEstoqueRepository::getMovimentacaoEstoquePorId($dataAjax['movimentacao_id']);
        $dataTables = DataTables::of($query)
            ->addColumn('id', function ($movimentacao) {
                return $movimentacao->id;
            })
            ->addColumn('estoque@produto@nome', function ($movimentacao) {
                return $movimentacao->estoque->produto->nome;
            })
            ->addColumn('estoque@produto@fabricante@nome', function ($movimentacao) {
                return $movimentacao->estoque->produto->fabricante->nome;
            })
            ->addColumn('estoque@quantidade_disponivel', function ($movimentacao) {
                return $movimentacao->estoque->quantidade_disponivel;
            })
            ->addColumn('quantidade_movimentada', function ($movimentacao) {
                return $movimentacao->quantidade_movimentada;
            })
            ->addColumn('tipo_movimentacao@descricao', function ($movimentacao) {
                return $movimentacao->tipo_movimentacao->descricao;
            });
        // Informa ao DataTables que a coluna 'acao' e 'ativo' contêm HTML
        if ($movimentacao->status_id !=  config('config.status.concluido')) {
            $dataTables->addColumn('acao', function ($movimentacao) {
                // Gerando o HTML do botão de edição
                return '<a href="#" class="btn btn-danger" style="color: #fff !important"
                                onclick="confirmDelete(' . $movimentacao->id . ')">
                                <i class="bi bi-trash"></i>
                            </a>';
            })
                ->rawColumns(['acao']);
        }
        return $dataTables  // Informa ao DataTables que a coluna 'acao' e 'ativo' contêm HTML
            ->make(true);
    }

    public function getPedidos(Request $request)
    {
        $usuario = auth()->user()->getUserModulo;
        $parans = Post::anti_injection_yajra($request->all());

        $query = new YajraQueryBuilder(Pedido::query());

        return $query->limit(100)->where('usuario_id', $usuario->id)
            ->where('loja_id', $usuario->loja_id)->reject(['usuario_id', 'qtd_itens', 'data_pedido'])
            ->whereYQB($parans->getAttributes())
            ->whereDateYQB(['data_pedido'])
            ->specifyColumnYQB('usuario_id', function ($q, $valor) use ($usuario) {
                return $q->where('usuario_id', $usuario->id);
            })->specifyColumnYQB('qtd_itens', function ($q, $valor) {
                return $q->withCount('pedido_itens') // Adiciona a contagem
                    ->having('pedido_itens_count', 'like', formataLikeSql($valor));
            })->constructColumns(function ($dataTables) {
                return $dataTables->addColumn('id', function ($pedido) {
                    return $pedido->id;
                })
                    ->addColumn('usuario_id', function ($pedido) {
                        return $pedido->usuario->master->name;
                    })
                    ->addColumn('status.descricao', function ($pedido) {
                        $status = $pedido->status->descricao();
                        $badgeClass = $pedido->status->badge;
                        return "<span class='$badgeClass'>$status</span>";
                    })
                    ->addColumn('data_limite', function ($pedido) {
                        return aplicarMascaraDataNascimento($pedido->data_limite);
                    })
                    ->addColumn('qtd_itens', function ($pedido) {
                        return $pedido->pedido_itens->count();
                    })
                    ->addColumn('observacao', function ($pedido) {
                        return $pedido->observacao;
                    })
                    ->addColumn('acao', function ($pedido) {
                        // Gerando o HTML do botão de edição
                        return '<a href="' . route('cadastro.pedido.edit', ['id' => $pedido->id]) . '" class="btn btn-warning">
                    <i class="bi bi-pencil"></i>
                </a>';
                    });
            });
    }

    public function getFabricantes(Request $request)
    {
        $empresa_id = auth()->user()->empresa_id;
        $parans = Post::anti_injection_yajra($request->all(), function ($item) {
            $coluna = $item['coluna'];
            $valor = $item['value'];
            if ($coluna == 'ativo' && $valor != null && $valor != '') {
                $item['value'] = stripos($valor, 'ina') !== false ? false : true;
            }
            return $item;
        });
        $query = new QueryBuilderMercado(new Fabricante());
        $query = $query->with('endereco')->limit(100)->where('empresa_master_cod', $empresa_id, '=')->construirQueryYajra($parans)->getQuery();

        return DataTables::of($query)
            ->addColumn('id', function ($fabricante) {
                return $fabricante->id;
            })
            ->addColumn('nome', function ($fabricante) {
                return $fabricante->nome;
            })
            ->addColumn('cnpj', function ($fabricante) {
                return aplicarMascaraDocumento($fabricante->cnpj);
            })
            ->addColumn('razao_social', function ($fabricante) {
                return $fabricante->razao_social;
            })
            ->addColumn('inscricao_estadual', function ($fabricante) {
                return $fabricante->inscricao_estadual;
            })
            ->addColumn('email', function ($fabricante) {
                return $fabricante->email;
            })
            ->addColumn('ativo', function ($fabricante) {
                $status = $fabricante->ativo ? 'Ativo' : 'Inativo';
                $badgeClass = $fabricante->ativo ? 'badge bg-primary' : 'badge bg-danger';
                return "<span class='$badgeClass'>$status</span>";
            })
            ->addColumn('acao', function ($fabricante) {
                // Gerando o HTML do botão de edição
                return '<a href="' . route('cadastro.fabricante.edit', ['fabricante_id' => $fabricante->id]) . '" class="btn btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>' .
                    '<button class="btn btn-info ml-1" onclick="mostrarFabricante(' . htmlspecialchars(json_encode($fabricante), ENT_QUOTES, 'UTF-8') . ')">
                            <i class="bi bi-eye"></i>
                        </button>';
            })
            ->rawColumns(['acao', 'ativo'])  // Informa ao DataTables que a coluna 'acao' e 'ativo' contêm HTML
            ->make(true);
    }

    public function getCotacoes(Request $request)
    {
        $usuario = auth()->user()->getUserModulo;
        $parans = Post::anti_injection_yajra($request->all());

        $query = new YajraQueryBuilder(Cotacao::query());

        return $query->limit(100)
            ->where('loja_id', $usuario->loja_id)->reject(['usuario_id', 'data_encerramento', 'data_abertura'])
            ->whereYQB($parans->getAttributes())
            ->whereDateYQB(['data_abertura', 'data_encerramento'])
            ->specifyColumnYQB('usuario_id', function ($q, $valor) use ($usuario) {
                return $q->where('usuario_id', $usuario->id);
            })->constructColumns(function ($dataTables) {
                return $dataTables->addColumn('id', function ($cotacao) {
                    return $cotacao->id;
                })
                    ->addColumn('usuario_id', function ($cotacao) {
                        return $cotacao->usuario->master->name;
                    })
                    ->addColumn('status.descricao', function ($cotacao) {
                        $status = $cotacao->status->descricao();
                        $badgeClass = $cotacao->status->badge(); // 'bg-primary' para azul, 'bg-danger' para vermelho
                        return "<span class='$badgeClass'>$status</span>";
                    })
                    ->addColumn('data_abertura', function ($cotacao) {
                        return formatarData($cotacao->data_abertura);
                    })
                    ->addColumn('data_encerramento', function ($cotacao) {
                        return formatarData($cotacao->data_encerramento);
                    })
                    ->addColumn('descricao', function ($cotacao) {
                        return $cotacao->descricao;
                    })
                    ->addColumn('acao', function ($cotacao) {
                        // Gerando o HTML do botão de edição
                        return '<a href="' . route('cadastro.cotacao.edit', ['cotacao_id' => $cotacao->id]) . '" class="btn btn-warning">
                    <i class="bi bi-pencil"></i>
                </a>';
                    });
            });
    }

    public function getCompras(Request $request)
    {
        $usuario = auth()->user()->getUserModulo;
        $parans = Post::anti_injection_yajra($request->all());

        $query = new YajraQueryBuilder(Compra::query());

        return $query->limit(100)
            ->where('loja_id', $usuario->loja_id)->reject(['usuario_id', 'data_abertura', 'previsao_entrega'])
            ->whereYQB($parans->getAttributes())
            ->whereDateYQB(['data_abertura', 'previsao_entrega'])
            ->specifyColumnYQB('usuario_id', function ($q, $valor) use ($usuario) {
                return $q->where('usuario_id', $usuario->id);
            })->constructColumns(function ($dataTables) {
                return $dataTables->addColumn('id', function ($compra) {
                    return $compra->id;
                })
                    ->addColumn('usuario_id', function ($compra) {
                        return $compra->usuario->master->name;
                    })
                    ->addColumn('status.descricao', function ($compra) {
                        $status = $compra->status->descricao();
                        $badgeClass = $compra->status->badge(); // 'bg-primary' para azul, 'bg-danger' para vermelho
                        return "<span class='$badgeClass'>$status</span>";
                    })
                    ->addColumn('cotacao.data_abertura', function ($compra) {
                        return aplicarMascaraDataNascimento($compra->cotacao->data_abertura);
                    })
                    ->addColumn('cot_fornecedor.previsao_entrega', function ($compra) {
                        return aplicarMascaraDataNascimento($compra->cot_fornecedor->previsao_entrega);
                    })
                    ->addColumn('cot_fornecedor.observacao', function ($compra) {
                        return $compra->cot_fornecedor->observacao ?? ' ';
                    })
                    ->addColumn('acao', function ($compra) {
                        // Gerando o HTML do botão de edição
                        return '<a href="' . route('cadastro.compra.edit', ['compra_id' => $compra->id]) . '" class="btn btn-warning">
                    <i class="bi bi-pencil"></i>
                </a>';
                    });
            });
    }
}
