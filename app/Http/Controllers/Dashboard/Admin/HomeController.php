<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Application\EmpresaApplication;
use App\Application\LojaApplication;
use App\Helpers\YajraQueryBuilder;
use App\Http\Controllers\ControllerBase;
use App\Models\Empresa;
use App\Services\BrasilApiService;
use App\System\Post;
use App\UseCases\Empresa\Requests\CriarEmpresaRequest;
use App\UseCases\Loja\Requests\CriarEnderecoLojaRequest;
use App\UseCases\Loja\Requests\CriarLojaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class HomeController extends ControllerBase
{

    /**
     * ESSE CONTROLLER CUIDA DE TUDO DA PARTE GECON "EMPRESAS, FUNCIONARIOS DAS EMPRESAS, USUSARIOS, CONFIGURAÇÕES ETC..."
     *
     *
     */

    public function index()
    {
        $columns = YajraQueryBuilder::constructColumnsView([
            ['id', '#'],
            ['nome_fantasia', 'Nome'],
            ['cnpj', 'CNPJ'],
            ['ativo', 'Ativo'],
            ['acao', 'Ação'],
        ]);
     
        return view('admin.empresas.index', compact('columns'));
    }

    public function create()
    {
        return view('admin.empresas.create', ['empresa' => false]);
    }

    public function store(Request $request)
    {
        $this->getDb()->begin();

        try {
            $parans = (object) Post::anti_injection_array($request->except('endereco_brasil_api'));

            $ativo = $request->ativo ? 1 : 0;
            $status_id = config('config.status.em_dia');
            $empresa = EmpresaApplication::criarEmpresa(new CriarEmpresaRequest(
                $parans->razao_social,
                $parans->nome_fantasia,
                $parans->cnpj,
                $ativo,
                $status_id
            ));
            $nome = 'Loja 1';
            $empresa_id = $empresa->id;
            $matriz = true;
            $cnpj = $empresa->cnpj;
            $modulo_id = $request->modulo_id;
            $status_id = config('config.status.em_dia');
            $telefone = $request->telefone ? $parans->telefone : null;
            $email = $request->email ? $parans->email : null;
            $endereco = json_decode($request->endereco_brasil_api);

            $enderecoRequest = new CriarEnderecoLojaRequest(
                $endereco->logradouro,
                $endereco->municipio,
                $endereco->bairro,
                $endereco->uf,
                $endereco->cep,
                $endereco->numero,
                $endereco->tipoLogradouro
            );
            $loja = LojaApplication::criaLoja(new CriarLojaRequest(
                $nome,
                $empresa_id,
                $matriz,
                $cnpj,
                $modulo_id,
                $status_id,
                $email,
                $telefone,
                $enderecoRequest
            ));

            $this->getDb()->commit();

            session()->flash('success', 'Empresa criada com sucesso.');
            return redirect()->route('admin.empresa.edit', ['empresa' => $empresa->id]);
        } catch (\Exception $e) {
            //adicionar controle de logs
            $this->getDb()->rollBack();
            session()->flash('error', 'Erro ao criar empresa!. Erro: ' . $e->getMessage());
            return view('admin.empresas.create');
        }
    }

    //recebe id da empresa no parametro
    public function edit($master)
    {
        $empresa = Empresa::find($master);
        if (!$empresa) {
            //empresa não existe
            session()->flash('error', 'Empresa não existe.');
            return redirect()->route('admin.empresa.index');
        }

        return view('admin.empresas.edit', ['empresa' => $empresa]);
    }

    //recebe o id da empresa
    public function update(Request $request, $master)
    {
        $this->getDb()->begin();

        try {
            $parans = (object) Post::anti_injection_array($request->all());
            $ativo = $request->ativo ? 1 : 0;
            $status_id = config('config.status.em_dia');

            $empresa = EmpresaApplication::editarEmpresa($master, new CriarEmpresaRequest($parans->razao_social, $parans->nome_fantasia, $parans->cnpj, $ativo, $status_id));

            $this->getDb()->commit();
            session()->flash('success', 'Empresa atualizada com sucesso.');
            return redirect()->route('admin.empresa.index');
        } catch (\Exception $e) {
            //adicionar controle de logs
            $this->getDb()->rollBack();
            session()->flash('error', 'Erro ao editar empresa!. Erro: ' . $e->getMessage());
            return redirect()->route('admin.empresa.index');
        }
    }

    public function getEmpresaBrasilApi(Request $request)
    {
        try {
            $service = new BrasilApiService();
            $response = $service->getEmpresa(Post::anti_injection($request->cnpj));
            if ($response->status != 200) {
                throw new \Exception($response->mensagem['message'], $response->status);
            }
            $empresa = $response->mensagem;

            return response()->json(['success' => true, 'msg' => 'Empresa rastreada com sucesso.', 'empresa' => $empresa]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
}
