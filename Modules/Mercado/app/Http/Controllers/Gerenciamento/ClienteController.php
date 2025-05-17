<?php

namespace Modules\Mercado\Http\Controllers\Gerenciamento;

use App\System\Post;
use Illuminate\Http\Request;
use Modules\Mercado\Application\ClienteApplication;
use Modules\Mercado\Entities\Cliente;
use Modules\Mercado\Http\Controllers\ControllerBaseMercado;
use Modules\Mercado\UseCases\Gerenciamento\Cliente\Requests\ClienteRequest;
use Modules\Mercado\UseCases\Gerenciamento\Endereco\Requests\EnderecoRequest;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class ClienteController extends ControllerBaseMercado
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('mercado::gerenciamento.cliente.index');
    }

    /**
     * Lista os clientes no datatables
     */
    public function listarClientes(Request $request)
    {
        /* convertendo valor para booleano */
        $ativo = filter_var($request->input('clientesInativos'), FILTER_VALIDATE_BOOLEAN);

        $clientes = ClienteApplication::getTodosClientes(!$ativo);

        return response()->json(['data' => $this->clientesParaDatatables($clientes)]);
    }

    /**
     * Formata os dados para listar no datatables
     */
    public function clientesParaDatatables($clientes)
    {
        $data = [];
        $dadosClientes = [];

        foreach ($clientes as $key => $cliente) {
            if ($cliente->endereco) {
                $endereco = $cliente->endereco->logradouro   .
                    ', Nº ' . $cliente->endereco->numero     .
                    ', '    . $cliente->endereco->bairro     .
                    ', '    . $cliente->endereco->cidade     .
                    '/'     . $cliente->endereco->uf         .
                    ', '    . aplicarMascaraCep($cliente->endereco->cep)        .
                    ', '    . $cliente->endereco->complemento;
            } else {
                $endereco = 'Não informado';
            }

            $dadosClientes = [
                'id' => $cliente->id,
                'nome' => $cliente->nome,
                'documento' => $cliente->documento,
                'status_id' => $cliente->status_id,
                'celular' => $cliente->celular,
                'telefone_fixo' => $cliente->telefone_fixo,
                'email' => $cliente->email,
                'data_nascimento' => $cliente->data_nascimento,
                'limite_credito' => $cliente->credito ? converterParaReais($cliente->credito->credito_loja) : 0,
                'endereco' => $endereco,
                'observacao' => $cliente->observacao,
            ];

            $botoes =
                '<a href="' . route('cadastro.cliente.edit', $cliente->id) . '" type="button" class="btn btn-warning mx-2">
                <i class="bi bi-pencil"></i> Editar
            </a>' .
                '<button type="button" id="mostrarDadosClientes" data-info="' . htmlspecialchars(json_encode($dadosClientes)) . '" class="btn btn-info mx-2">
                <i class="bi bi-eye"></i> Ver
            </button>';

            $data[] = [
                $cliente->id,
                $cliente->nome,
                aplicarMascaraDocumento($cliente->documento),
                // $this->getStatus($cliente->status_id),
                '<span class=" badge badge-' . $cliente->getStatus(true) . ' "> ' . $cliente->getStatus() . ' </span>',
                aplicarMascaraCelular($cliente->celular),
                aplicarMascaraTelefoneFixo($cliente->telefone_fixo),
                $cliente->email,
                aplicarMascaraDataNascimento($cliente->data_nascimento),
                $cliente->credito ? converterParaReais($cliente->credito->credito_loja) : 0,
                $endereco,
                $botoes
            ];
        }

        return $data;
    }

    /**
     * Obtém os nomes dos status e o estilo a ser usado para cada status
     */
    function getStatus($status) {}

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('mercado::gerenciamento.cliente.create', ['cliente' => false, 'endereco' => false]);
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
            $ativo = filter_var($parans->ativo, FILTER_VALIDATE_BOOLEAN);
            $parans->limite_credito = $request->limite_credito ? converteExibicaoParaCentavos($parans->limite_credito) : 0;

            $historicoRequest = $this->getCriarHistoricoRequest($request);
            $cliente = ClienteApplication::criarCliente(
                new ClienteRequest(
                    $historicoRequest,
                    auth()->user()->usuarioMercado->loja->empresa_master_cod,
                    $parans->nome,
                    limparCaracteres($parans->documento),
                    $parans->pessoa,
                    $ativo,
                    $parans->status,
                    limparCaracteres($parans->celular),
                    limparCaracteres($parans->telefone_fixo),
                    $parans->email,
                    converterDataParaSalvarNoBanco($parans->data_nascimento),
                    $parans->limite_credito,
                    $parans->observacao,
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

            return response()->json(['message' => 'Cliente cadastrado com sucesso.']);
        } catch (\Exception $e) {

            $this->getDb()->rollBack();
            return response()->json(['message' => 'Erro ao cadastrar cliente! Erro: ' . $e->getMessage()], 500);
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
        $cliente = ClienteApplication::getClienteById($id);
        $endereco = $cliente->endereco;
        return view('mercado::gerenciamento.cliente.edit', ['cliente' => $cliente, 'endereco' => $endereco]);
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
            $parans->limite_credito = $request->limite_credito ? converteExibicaoParaCentavos($parans->limite_credito) : 0;
            $historicoRequest = $this->getCriarHistoricoRequest($request);

            ClienteApplication::atualizarCliente(
                new ClienteRequest(
                    $historicoRequest,
                    auth()->user()->usuarioMercado->loja->empresa_master_cod,
                    $parans->nome,
                    limparCaracteres($parans->documento),
                    $parans->pessoa,
                    $ativo,
                    $parans->status,
                    limparCaracteres($parans->celular),
                    limparCaracteres($parans->telefone_fixo) == '' ? null : limparCaracteres($parans->telefone_fixo),
                    $parans->email == '' ? null : $parans->email,
                    converterDataParaSalvarNoBanco($parans->data_nascimento) == '' ? null : converterDataParaSalvarNoBanco($parans->data_nascimento),
                    $parans->limite_credito,
                    $parans->observacao == '' ? null : $parans->observacao,
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
                ),
                $id
            );

            // DB::connection(config('database.connections.mercado.database'))->commit();
            $this->getDb()->commit();

            return response()->json(['message' => 'Cliente atualizado com sucesso.']);
        } catch (\Exception $e) {
            // DB::connection(config('database.connections.mercado.database'))->rollBack();
            $this->getDb()->rollBack();

            return response()->json(['message' => 'Erro ao atualizar cliente! Erro: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id) {}

    public function getCliente(Request $request)
    {
        $cliente = Cliente::with(['status', 'credito', 'endereco'])->where('empresa_master_cod', auth()->user()->empresa_id)->find(Post::anti_injection($request->id));
        $cliente->status->badge = $cliente->status->badge();
        if (!$cliente) {
            return response()->json(['success' => false, 'msg' => 'Cliente não encontrado.']);
        } else {
            return response()->json(['success' => true, 'cliente' => $cliente, 'msg' => 'Cliente encontrado.']);
        }
    }
}
