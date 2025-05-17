<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Application\LojaApplication;
use App\Http\Controllers\ControllerBase;
use App\Models\Empresa;
use App\Models\Loja as ModelsLoja;
use App\Repository\Loja\LojaRepository;
use App\System\Post;
use App\UseCases\Loja\Requests\CriarEnderecoLojaRequest;
use App\UseCases\Loja\Requests\CriarLojaRequest;
use Illuminate\Http\Request;
use Modules\Mercado\Entities\Loja;

class LojaController extends ControllerBase
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($empresa_id)
    {
        $empresa = Empresa::find($empresa_id);
        return view('admin.lojas.create', ['loja' => null, 'empresa' => $empresa]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($empresa_id, Request $request)
    {
        $this->getDb()->begin();
        try {
            $parans = (object) Post::anti_injection_array($request->all());
            $empresa = Empresa::find($empresa_id);
            $nome = $parans->nome;
            $empresa_id = $empresa_id;
            $matriz = false;
            $cnpj = preg_replace('/[^0-9]/', '', $parans->cnpj);
            $modulo_id = $empresa->matriz->modulo_id;
            $status_id = $parans->status;
            $endereco = null;

            if ($request->logradouro) {
                $endereco = new CriarEnderecoLojaRequest(
                    $parans->logradouro,
                    $parans->cidade,
                    $parans->bairro,
                    $parans->uf,
                    $parans->cep,
                    $request->numero ? $parans->numero : null,
                    $request->complemento ? $parans->complemento : null
                );
            }

            $loja = LojaApplication::criaLoja(new CriarLojaRequest(
                $nome,
                $empresa_id,
                $matriz,
                $cnpj,
                $modulo_id,
                $status_id,
                $endereco
            ));

            $this->getDb()->commit();

            session()->flash('success', 'Loja criada com sucesso.');
            return redirect()->back();
        } catch (\Exception $e) {
            //adicionar controle de logs
            $this->getDb()->rollBack();

            session()->flash('error', 'Erro ao criar loja!. Erro: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $loja = ModelsLoja::find($id);

        if (!$loja->empresa) {
            session()->flash('error', 'Loja desvinculada a alguma empresa!.');
            return redirect()->back();
        }
        return view('admin.lojas.edit', ['loja' => $loja, 'empresa' => $loja->empresa]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param  int  $empresa_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $empresa_id, $id)
    {

        $this->getDb()->begin();

        try {
            $parans = (object) Post::anti_injection_array($request->all());
            $empresa = Empresa::find($empresa_id);
            $loja = LojaRepository::getLojaById($id);
            $nome = $parans->nome;
            $empresa_id = $empresa_id;
            $matriz = $request->matriz == 'on' ? true : $loja->matriz;
            $cnpj = preg_replace('/[^0-9]/', '', $parans->cnpj);
            $modulo_id = $empresa->matriz->modulo_id;
            $status_id = $parans->status;
            $endereco = null;
            $email = $request->email;
            $telefone = $request->telefone;
            $endereco = null;

            if ($request->logradouro) {
                $endereco = new CriarEnderecoLojaRequest(
                    $parans->logradouro,
                    $parans->cidade,
                    $parans->bairro,
                    $parans->uf,
                    $parans->cep,
                    $request->numero ? $parans->numero : null,
                    $request->complemento ? $parans->complemento : null
                );
            }

            $loja = LojaApplication::atualizaLoja($id, new CriarLojaRequest(
                $nome,
                $empresa_id,
                $matriz,
                $cnpj,
                $modulo_id,
                $status_id,
                $email,
                $telefone,
                $endereco
            ));

            $this->getDb()->commit();
            session()->flash('success', 'Loja editada com sucesso.');
            return redirect()->back();
        } catch (\Exception $e) {
            //adicionar controle de logs
            $this->getDb()->rollBack();
            session()->flash('error', 'Erro ao editar loja!. Erro: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
