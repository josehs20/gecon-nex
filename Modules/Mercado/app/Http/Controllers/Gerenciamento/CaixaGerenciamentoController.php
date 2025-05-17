<?php

namespace Modules\Mercado\Http\Controllers\Gerenciamento;

use App\System\Post;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Mercado\Application\CaixaApplication;
use Modules\Mercado\Entities\Recurso;
use Modules\Mercado\Http\Controllers\ControllerBaseMercado;
use Modules\Mercado\Repository\Caixa\CaixaRepository;
use Modules\Mercado\UseCases\Gerenciamento\Caixa\Requests\AtualizarCaixaRequest;
use Modules\Mercado\UseCases\Gerenciamento\Caixa\Requests\CriarCaixaRequest;

class CaixaGerenciamentoController extends ControllerBaseMercado
{
    public function index()
    {
        // $caixas = CaixaRepository::getCaixas();

        return view('mercado::gerenciamento.caixa.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {

        return view('mercado::gerenciamento.caixa.create', ['caixa' => false]);
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


            $status_id = config('config.status.fechado');

            $caixa = CaixaApplication::criar_caixa(new CriarCaixaRequest(
                $this->getCriarHistoricoRequest($request),
                $parans->nome,
                $status_id,
                [auth()->user()->getUserModulo->loja_id]
            ));

            $caixa = reset($caixa); // Usa reset()
            $this->getDb()->commit();
            session()->flash('success', 'Caixa criado com sucesso.');

            return redirect()->route('cadastro.caixa.edit', ['id' => $caixa->id]);
        } catch (\Exception $e) {

            $this->getDb()->rollBack();
            Log::error($e);
            session()->flash('error', 'Erro ao criar caixa!. Erro: ' . $e->getMessage());

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
        $caixa = CaixaRepository::getCaixaById($id);
        $recursos = Recurso::get();

        return view('mercado::gerenciamento.caixa.edit', ['caixa' => $caixa, 'recursos' => $recursos]);
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
            $status_id = config('config.status.fechado');
            $ativo = $request->ativo ? $parans->ativo : false;

            $caixas = CaixaApplication::atualizar_caixa(new AtualizarCaixaRequest(
                $this->getCriarHistoricoRequest($request),
                $id,
                $ativo,
                $parans->nome,
                $status_id,
            ));

            $this->getDb()->commit();
            session()->flash('success', 'Caixa alterado com sucesso.');

            return redirect()->route('cadastro.caixa.index');
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            session()->flash('error', 'Erro ao atualizar caixa produto!. Erro: ' . $e->getMessage());
            Log::error($e);
            return redirect()->back();
        }
    }

    public function create_recursos_caixa(Request $request, $id)
    {
        $this->getDb()->begin();

        try {
            $parans = (object)Post::anti_injection_array($request->all());

            $caixa = CaixaApplication::criaOuAtualizaRecursosCaixa(
                $parans->recursos,
                $id,
                $this->getCriarHistoricoRequest($request)
            );

            $this->getDb()->commit();

            return response()->json(['success' => true, 'msg' => 'Recursos atualizados com sucesso.']);
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            Log::error($e);
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function create_caixa_permissoes(Request $request, $id)
    {
        $this->getDb()->begin();

        try {
            $parans = (object)Post::anti_injection_array($request->all());
            $superior = filter_var($request->superior, FILTER_VALIDATE_BOOLEAN);

            $caixa_permissao = CaixaApplication::criaOuAtualizaPermissaoCaixa(
                $parans->usuario_id,
                $id,
                $superior,
                $this->getCriarHistoricoRequest($request)
            );

            $this->getDb()->commit();
            return response()->json(['success' => true, 'msg' => 'Permissão adicionada com sucesso.']);
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            Log::error($e);
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function delete_caixa_permissoes(Request $request)
    {
        $this->getDb()->begin();

        try {
            $parans = (object)Post::anti_injection_array($request->all());

            $caixa_permissao = CaixaApplication::deletePermissaoCaixa(
                $parans->caixa_permissao_id,
                $this->getCriarHistoricoRequest($request)
            );

            $this->getDb()->commit();
            return response()->json(['success' => true, 'msg' => 'Permissão deletada com sucesso.']);
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            Log::error($e);
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function get_usuarios_permissao_caixa(Request $request)
    {
        $parans = (object) Post::anti_injection_array($request->all());
        $caixa_id = $parans->caixa_id;

        $caixas = CaixaRepository::getCaixaPermissoes(auth()->user()->getUserModulo->loja_id, $caixa_id);

        $caixa_permissoes = $caixas->pluck('permissoes')->flatten()->map(function ($p) {
            return [
                $p->id,
                $p->usuario->master->name,
                $p->superior ? 'Sim' : 'Não',
                '<button type="button" class="btn btn-danger btn-sm" onclick="excluirPermissao(' . $p->id . ')">
                    <i class="bi bi-trash"></i> Excluir
                </button>'
            ];
        });

        return response()->json(['data' => $caixa_permissoes]);
    }

    public function get_usuarios(Request $request)
    {
        $parans = (object) Post::anti_injection_array($request->all());
        $caixa_id = $parans->options['caixa_id'];
        $usuarios = CaixaRepository::getUsuarios(auth()->user()->getUserModulo->loja_id, ($request->q ? $parans->q : ''));
        $usuarios_caixa = $usuarios->filter(function ($item) use ($caixa_id){
            return !$item->caixa_permissoes->contains('caixa_id', $caixa_id);
        });

        $select = $usuarios_caixa->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->master->name . ' - ' . $item->master->tipoUsuario->descricao
            ];
        })->values();
        // dd($select);
        return response()->json($select, 200);
    }
}
