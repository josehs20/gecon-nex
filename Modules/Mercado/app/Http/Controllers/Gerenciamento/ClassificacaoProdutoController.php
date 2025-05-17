<?php

namespace Modules\Mercado\Http\Controllers\Gerenciamento;

use App\System\Post;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Mercado\Application\ClassificacaoProdutoApplication;
use Modules\Mercado\Http\Controllers\ControllerBaseMercado;
use Modules\Mercado\Repository\ClassificacaoProduto\ClassificacaoProdutoRepository;
use Modules\Mercado\UseCases\Gerenciamento\ClassificacaoProduto\Requests\CriarClassificacaoProdutoRequest;
use Modules\Mercado\UseCases\Gerenciamento\ClassificacaoProduto\Requests\UpdateClassificacaoProdutoRequest;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class ClassificacaoProdutoController extends ControllerBaseMercado
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        // $classificacao_produto = ClassificacaoProdutoRepository::getCpLikeDescricao();
        return view('mercado::gerenciamento.classificacao_produto.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('mercado::gerenciamento.classificacao_produto.create');
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
            $empresaId = auth()->user()->empresa_id;

            $requestCp = new CriarClassificacaoProdutoRequest($this->getCriarHistoricoRequest($request), $parans->nome, $empresaId);

            /**
             * executa
             */

            $classificacao = ClassificacaoProdutoApplication::criarClassificacaoProduto($requestCp);
            $this->getDb()->commit();

            session()->flash('success', 'Classificacão de produto cadastrada com sucesso.');

            return redirect()->route('cadastro.classificacao_produto.create');
        } catch (\Exception $e) {
            $this->getDb()->rollBack();

            session()->flash('error', 'Erro ao criar classificação de produto!. Erro: ' . $e->getMessage());
            Log::error($e);
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $classificacao = ClassificacaoProdutoRepository::getClassificaoById($id);
        return view('mercado::gerenciamento.classificacao_produto.edit', ['classificacao' => $classificacao]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $this->getDb()->begin();

        try {
            $parans = (object)Post::anti_injection_array($request->all());

            $empresaId = auth()->user()->empresa_id;

            $requestCp = new UpdateClassificacaoProdutoRequest($this->getCriarHistoricoRequest($request), $id, $parans->nome, $empresaId);

            /**
             * executa
             */

            $classificacao_produto = ClassificacaoProdutoApplication::editarClassificacaoProduto($requestCp);
            $this->getDb()->commit();
            session()->flash('success', 'Classificacão de produto alterada com sucesso.');

            return redirect()->route('cadastro.classificacao_produto.index');
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            session()->flash('error', 'Erro ao atualizar classificação de produto!. Erro: ' . $e->getMessage());

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
        $unidadeMedidas = ClassificacaoProdutoRepository::getCpLikeDescricao(Post::anti_injection($request->q));
        $select = $unidadeMedidas->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->descricao
            ];
        });

        return response()->json($select);
    }
}
