<?php

namespace Modules\Mercado\Http\Controllers\Estoque;

use App\System\Post;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Mercado\Application\EstoqueApplication;
use Modules\Mercado\Http\Controllers\ControllerBaseMercado;
use Modules\Mercado\Repository\Estoque\EstoqueRepository;
use Modules\Mercado\Repository\Fabricante\FabricanteRepository;
use Modules\Mercado\UseCases\Gerenciamento\Estoque\Requests\UpdateQtdMinMaxRequest;

class EstoqueController extends ControllerBaseMercado
{
    public function index()
    {
        // $loja_id = auth()->user()->usuarioMercado->loja_id;
        // $estoques = EstoqueRepository::getTodosOsEstoques($loja_id);

        return view('mercado::estoque.estoque.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $lojas = auth()->user()->usuarioMercado->lojas;
        return view('mercado::estoque.estoque.create', ['lojas' => $lojas, 'estoque' => false]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {

    }

    public function edit($id)
    {
        $estoque = EstoqueRepository::getEstoqueById(Post::anti_injection($id));
        $fabricantes = FabricanteRepository::getFabricanteLikeNome();

        return view('mercado::estoque.estoque.edit', ['estoque' => $estoque]);
    }

    /**
     * Atualiza as configurações do estoque
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

            $quantidadeMin = formatarQtdRequest($parans->quantidade_minima);
            $quantidadeMax = formatarQtdRequest($parans->quantidade_maxima);
            $localizacao = $request->localizacao ? $parans->localizacao : null;

            $estoque = EstoqueApplication::updateQtdMinMax(new UpdateQtdMinMaxRequest($this->getCriarHistoricoRequest($request),$id, $quantidadeMin, $quantidadeMax, $localizacao));
            $this->getDb()->commit();

            session()->flash('success', 'Estoque atualizado com sucesso.');

            return redirect()->route('cadastro.estoque.index');
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            Log::error($e);
            session()->flash('error', 'Erro ao atualizar estoque!. Erro: ' . $e->getMessage());

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
}
