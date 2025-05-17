<?php

namespace Modules\Mercado\Http\Controllers\Gerenciamento;

use App\Services\GtinService;
use App\System\Post;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Mercado\Application\EstoqueApplication;
use Modules\Mercado\Application\ProdutoApplication;
use Modules\Mercado\Entities\NCM;
use Modules\Mercado\Http\Controllers\ControllerBaseMercado;
use Modules\Mercado\Repository\Fabricante\FabricanteRepository;
use Modules\Mercado\Repository\Produto\ProdutoRepository;
use Modules\Mercado\UseCases\Gerenciamento\Estoque\Requests\AtualizaNCMRequest;
use Modules\Mercado\UseCases\Gerenciamento\Produto\Requests\CriarProdutoRequest;
use Modules\Mercado\UseCases\Gerenciamento\Produto\Requests\EditarProdutoRequest;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Yajra\DataTables\DataTables;

class ProdutoController extends ControllerBaseMercado
{
    public function index()
    {
        return view('mercado::gerenciamento.produto.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $lojas = auth()->user()->usuarioMercado->lojas;
        $fabricantes = FabricanteRepository::getFabricanteLikeNome();

        return view('mercado::gerenciamento.produto.create', ['lojas' => $lojas, 'fabricantes' => $fabricantes, 'produto' => false, 'cod_aux' => ProdutoApplication::gerarCodAux(auth()->user()->usuarioMercado->loja_id)]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $this->getDb()->begin();
        try {
            $parans = (object)Post::anti_injection_array($request->all());

            $descricao = $request->descricao ? $parans->descricao : null;

            $requestPrduto = new CriarProdutoRequest(
                $parans->nome,
                converteDinheiroParaFloat($parans->preco_custo),
                converteDinheiroParaFloat($parans->preco_venda),
                $parans->cod_barras,
                $parans->cod_aux,
                $parans->unidade_medida,
                $parans->classificacao_id,
                $parans->lojas,
                $parans->data_validade,
                $parans->fabricante_id,
                $descricao,
                $this->getCriarHistoricoRequest($request)
            );

            /**
             * executa
             */

            $produto = ProdutoApplication::criarProduto($requestPrduto);

            $this->getDb()->commit();
            session()->flash('success', 'Produto cadastrado com sucesso.');

            return redirect()->route('cadastro.produto.edit', ['id' => $produto->id]);
        } catch (\Exception $e) {
            $this->getDb()->rollBack();

            Log::error($e);;
            session()->flash('error', 'Erro ao criar produto!. Erro: ' . $e->getMessage());

            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $produto = ProdutoRepository::getProdutoById(Post::anti_injection($id));
        $fabricantes = FabricanteRepository::getFabricanteLikeNome();

        return view('mercado::gerenciamento.produto.edit', ['produto' => $produto, 'fabricantes' => $fabricantes]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update($id, Request $request)
    {
        $this->getDb()->begin();

        try {
            $parans = (object)Post::anti_injection_array($request->all());

            $requestPrduto = new EditarProdutoRequest(
                $id,
                $parans->nome,
                converteDinheiroParaFloat($parans->preco_custo),
                converteDinheiroParaFloat($parans->preco_venda),
                $parans->cod_barras,
                $parans->cod_aux,
                $parans->unidade_medida,
                $parans->classificacao_id,
                $request->lojas ? $parans->lojas : [],
                $parans->data_validade,
                $parans->fabricante_id,
                $parans->descricao,
                $this->getCriarHistoricoRequest($request)
            );

            /**
             * executa
             */

            $produto = ProdutoApplication::editarProduto($requestPrduto);

            $this->getDb()->commit();

            session()->flash('success', 'Produto editado com sucesso.');

            return redirect()->route('cadastro.produto.index');
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            Log::error($e);
            session()->flash('error', 'Erro ao editar produto!. Erro: ' . $e->getMessage());

            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function select2(Request $request)
    {
        $produtos = ProdutoRepository::getProdutoByNomeAndCodAux(Post::anti_injection($request->q));
        $select = $produtos->take('50')->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->descricao
            ];
        });

        return response()->json($select);
    }

    public function estoqueSelect2(Request $request)
    {
        $q = Post::anti_injection($request->q);
        $produtos = ProdutoRepository::getProdutoByNomeAndCodAux($q);
        $select = $produtos->map(function ($item) {
            return [
                'id' => $item->estoque_id,
                'text' => $item->nome . ' - ' . $item->sigla . ' - ' . $item->fabricante_nome
            ];
        });

        return response()->json($select, 200);
    }

    public function get_produtos_yajra(Request $request)
    {
        $parans = Post::anti_injection_array($request->all());
        //pega somente o valor e coluna do yajra
        $parans = array_map(function ($column) {
            return [
                'value' => $column['search']['value'],
                'coluna' => $column['data'],
            ];
        }, $parans['columns']);

        $query = ProdutoRepository::getProdutosYajra($parans);

        return  DataTables::of($query)
            ->addColumn('id', function ($produto) {
                return $produto->id;
            })
            ->addColumn('nome', function ($produto) {
                return strtoupper($produto->nome); // Exemplo: Nome em maiúsculas
            })
            ->addColumn('loja_nome', function ($produto) {
                return $produto->loja_nome;
            })
            ->addColumn('custo', function ($produto) {
                return 'R$ ' . $produto->custo(); // Formatar custo
            })
            ->addColumn('preco', function ($produto) {
                return 'R$ ' . $produto->preco(); // Formatar preço
            })
            ->addColumn('fabricante_nome', function ($produto) {
                return $produto->fabricante_nome;
            })
            ->addColumn('cod_aux', function ($produto) {
                return $produto->cod_aux;
            })
            ->addColumn('sigla', function ($produto) {
                return $produto->un;
            })
            ->addColumn('classificacao', function ($produto) {
                return $produto->classificacao;
            })->addColumn('acao', function ($produto) {
                // Gerando o HTML do botão de edição
                return '<a href="' . route('cadastro.produto.edit', ['id' => $produto->id]) . '" class="btn btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>';
            })
            ->rawColumns(['acao'])  // Informa ao DataTables que a coluna acao contém HTML
            ->make(true);
    }

    public function get_gtin(Request $request)
    {
        $service = new GtinService();
        return $service->getGtin(Post::anti_injection($request->cod_barras));
    }

    public function get_ncms(Request $request)
    {
        return NCM::where(function ($query) use ($request) {
            // Remove os pontos da string de pesquisa
            $termo = str_replace('.', '', Post::anti_injection($request->q));

            // Realiza a consulta ignorando os pontos na coluna "codigo"
            $query->where(DB::raw('REPLACE(codigo, ".", "")'), 'like', '%' . $termo . '%')
                ->orWhere('descricao', 'like', formataLikeSql($termo));
        })
            ->select('id', DB::raw('CONCAT(codigo, " - ", descricao) as text')) // Concatena "codigo - descricao"
            ->limit(50)
            ->get();
    }

    public function post_ncms(Request $request, $estoque_id)
    {
        $this->getDb()->begin();
        try {
            $parans = (object)Post::anti_injection_array($request->all());
            $ncm_id = $parans->ncm;
            $estoque = EstoqueApplication::atualizaNCM(new AtualizaNCMRequest($ncm_id, $estoque_id, $this->getCriarHistoricoRequest($request)));
            // $this->getDb()->commit();
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            return true;
            dd($e);
            //throw $th;
        }
    }
}
