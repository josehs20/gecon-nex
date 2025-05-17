<?php

namespace Modules\Mercado\Http\Controllers\Gerenciamento;

use App\System\Post;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Mercado\Application\UnidadeMedidaApplication;
use Modules\Mercado\Http\Controllers\ControllerBaseMercado;
use Modules\Mercado\Repository\UnidadeMedida\UnidadeMedidaRepository;
use Modules\Mercado\UseCases\Gerenciamento\UnidadeMedida\Requests\CriarUnidadeMedidaRequest;
use Modules\Mercado\UseCases\Gerenciamento\UnidadeMedida\Requests\EditarUnidadeMedidaRequest;

class UnidadeMedidaController extends ControllerBaseMercado
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('mercado::gerenciamento.unidade_medida.index');

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {


        return view('mercado::gerenciamento.unidade_medida.create', ['unidadeMedida' => false]);
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
            $parans->pode_ser_float = $request->pode_ser_float ? true : false;
            $empresa_id = auth()->user()->empresa_id;
            $requestUn = new CriarUnidadeMedidaRequest($this->getCriarHistoricoRequest($request),$parans->nome, $parans->sigla, $parans->pode_ser_float, $empresa_id);

            /**
             * executa
             */

            $produto = UnidadeMedidaApplication::criarUnidadeMedida($requestUn);
            $this->getDb()->commit();
            session()->flash('success', 'Unidade medida cadastrada com sucesso.');

            return redirect()->route('cadastro.unidade_medida.create');
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            session()->flash('error', 'Erro ao criar unidade de medida!. Erro: ' . $e->getMessage());
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
        $unidadeMedida = UnidadeMedidaRepository::getUnById(Post::anti_injection($id));
        return view('mercado::gerenciamento.unidade_medida.edit', ['unidadeMedida' => $unidadeMedida]);
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
            $parans->pode_ser_float = $request->pode_ser_float ? true : false;
            $empresa_id = auth()->user()->empresa_id;

            $requestUn = new EditarUnidadeMedidaRequest($this->getCriarHistoricoRequest($request),$id,$parans->nome, $parans->sigla, $parans->pode_ser_float, $empresa_id);

            /**
             * executa
             */

            $produto = UnidadeMedidaApplication::editarUnidadeMedida($requestUn);
            $this->getDb()->commit();
            session()->flash('success', 'Unidade medida editada com sucesso.');

            return redirect()->route('cadastro.unidade_medida.index');
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            session()->flash('error', 'Erro ao editar unidade de medida!. Erro: ' . $e->getMessage());
            Log::error($e);
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
        $unidadeMedidas = UnidadeMedidaRepository::getUnLikeDescricao(Post::anti_injection($request->q));
        $select = $unidadeMedidas->map(function($item){
                return [
                    'id' => $item->id,
                    'text' =>$item->descricao
                ];
        });

        return response()->json($select);
    }
}
