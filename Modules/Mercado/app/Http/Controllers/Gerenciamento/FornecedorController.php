<?php

namespace Modules\Mercado\Http\Controllers\Gerenciamento;

use App\System\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Mercado\Application\FornecedorApplication;
use Modules\Mercado\Entities\Fornecedor;
use Modules\Mercado\Http\Controllers\ControllerBaseMercado;
use Modules\Mercado\Repository\Fornecedor\FornecedorRepository;
use Modules\Mercado\UseCases\Gerenciamento\Endereco\Requests\EnderecoRequest;
use Modules\Mercado\UseCases\Gerenciamento\Fornecedor\Requests\FornecedorRequest;

class FornecedorController extends ControllerBaseMercado
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('mercado::gerenciamento.fornecedor.index');
    }

    /**
     * Lista os fornecedores no datatables
     */
    public function listarFornecedores(Request $request)
    {
        /* convertendo valor para booleano */
        $ativo = filter_var($request->input('fornecedoresInativos'), FILTER_VALIDATE_BOOLEAN);

        $fornecedores = FornecedorApplication::getTodosFornecedores(!$ativo);

        return response()->json(['data' => $this->fornecedoresParaDatatables($fornecedores)]);
    }

    /**
     * Formata os dados para listar no datatables
     */
    public function fornecedoresParaDatatables($fornecedores)
    {
        $data = [];
        $dadosFornecedor = [];

        foreach ($fornecedores as $key => $fornecedor) {
            $endereco = $fornecedor->endereco->logradouro   .
                ', Nº ' . $fornecedor->endereco->numero     .
                ', '    . $fornecedor->endereco->bairro     .
                ', '    . $fornecedor->endereco->cidade     .
                '/'     . $fornecedor->endereco->uf         .
                ', '    . aplicarMascaraCep($fornecedor->endereco->cep)        .
                ', '    . $fornecedor->endereco->complemento;

            $dadosFornecedor = [
                'id' => $fornecedor->id,
                'nome' => $fornecedor->nome,
                'nome_fantasia' => $fornecedor->nome_fantasia,
                'documento' => $fornecedor->documento,
                'celular' => $fornecedor->celular,
                'telefone_fixo' => $fornecedor->telefone_fixo,
                'email' => $fornecedor->email,
                'site' => $fornecedor->site,
                'endereco' => $endereco,
            ];

            $botoes =
                '<a href="' . route('cadastro.fornecedor.edit', $fornecedor->id) . '" type="button" class="btn btn-warning mx-2">
                <i class="bi bi-pencil"></i> Editar
            </a>' .
                '<button type="button" id="mostrarDadosFornecedor" data-info="' . htmlspecialchars(json_encode($dadosFornecedor)) . '" class="btn btn-info mx-2">
                <i class="bi bi-eye"></i> Ver
            </button>';

            $data[] = [
                $fornecedor->id,
                $fornecedor->nome,
                $fornecedor->nome_fantasia,
                aplicarMascaraDocumento($fornecedor->documento),
                aplicarMascaraCelular($fornecedor->celular),
                aplicarMascaraTelefoneFixo($fornecedor->telefone_fixo),
                $fornecedor->email,
                $fornecedor->site,
                $endereco,
                $botoes
            ];
        }

        return $data;
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('mercado::gerenciamento.fornecedor.create', ['fornecedor' => false, 'endereco' => false]);
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
            $historicoRequest = $this->getCriarHistoricoRequest($request);

            FornecedorApplication::criarFornecedor(
                new FornecedorRequest(
                    $historicoRequest,
                    auth()->user()->usuarioMercado->loja->empresa_master_cod,
                    $parans->nome,
                    $parans->nome_fantasia,
                    limparCaracteres($parans->documento),
                    $parans->pessoa,
                    $parans->ativo,
                    limparCaracteres($parans->celular),
                    $parans->telefone_fixo == '' ? null : limparCaracteres($parans->telefone_fixo),
                    $parans->email == '' ? null : $parans->email,
                    $parans->site == '' ? null : $parans->site,
                    new EnderecoRequest(
                        $historicoRequest,
                        $parans->logradouro,
                        $parans->cidade,
                        $parans->bairro,
                        $parans->uf,
                        limparCaracteres($parans->cep),
                        $parans->numero,
                        $parans->complemento == '' ? null : $parans->complemento
                    )
                )
            );

            $this->getDb()->commit();
            return response()->json(['message' => 'Fornecedor cadastrado com sucesso.']);
        } catch (\Exception $e) {
            $this->getDb()->rollBack();

            Log::error($e);
            return response()->json(['message' => 'Erro ao cadastrar fornecedor! Erro: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id) {}

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $fornecedor = FornecedorApplication::getFornecedorById($id);
        $endereco = $fornecedor->endereco;
        return view('mercado::gerenciamento.fornecedor.edit', ['fornecedor' => $fornecedor, 'endereco' => $endereco]);
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
            $ativo = filter_var($parans->ativo, FILTER_VALIDATE_BOOLEAN);
            $historicoRequest = $this->getCriarHistoricoRequest($request);

            FornecedorApplication::atualizarFornecedor(new FornecedorRequest(
                $historicoRequest,
                auth()->user()->usuarioMercado->loja->empresa_master_cod,
                $parans->nome,
                $parans->nome_fantasia,
                limparCaracteres($parans->documento),
                $parans->pessoa,
                $ativo,
                limparCaracteres($parans->celular),
                $parans->telefone_fixo == '' ? null : limparCaracteres($parans->telefone_fixo),
                $parans->email == '' ? null : $parans->email,
                $parans->site == '' ? null : $parans->site,
                new EnderecoRequest(
                    $historicoRequest,
                    $parans->logradouro,
                    $parans->cidade,
                    $parans->bairro,
                    $parans->uf,
                    limparCaracteres($parans->cep),
                    $parans->numero,
                    $parans->complemento == '' ? null : $parans->complemento
                )
            ), $id);

            $this->getDb()->commit();
            session()->flash('success', 'Fornecedor atualizado com sucesso.');

            return redirect()->route('cadastro.fornecedor.index');
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            session()->flash('success', 'Erro ao atualizar fornecedor: ', $e->getMessage());
            return redirect()->back();

        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id) {}
    public function getFornecedor(Request $request)
    {
        $fornecedor = Fornecedor::with(['endereco'])->find(Post::anti_injection($request->id));

        if (!$fornecedor) {
            return response()->json(['success' => false, 'msg' => 'Fornecedor não encontrado.']);
        } else {
            return response()->json(['success' => true, 'fornecedor' => $fornecedor, 'msg' => 'Fornecedor encontrado.']);
        }
    }

    public function getFornecedores(Request $request)
    {
        $empresa_id = auth()->user()->empresa_id;
        $fornecedores = FornecedorRepository::getFornecedores($empresa_id, Post::anti_injection($request->q), 50);
        $select = $fornecedores->map(function ($f) {
            return [
                'id' => $f->id,
                'text' => $f->nomeFormatado()
            ];
        });
        
        return response()->json($select, 200);
    }
}
