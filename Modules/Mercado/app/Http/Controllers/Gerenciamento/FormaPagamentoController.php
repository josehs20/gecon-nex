<?php

namespace Modules\Mercado\Http\Controllers\Gerenciamento;

use App\System\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Mercado\Application\PagamentoApplication;
use Modules\Mercado\Entities\EspeciePagamento;
use Modules\Mercado\Http\Controllers\ControllerBaseMercado;
use Modules\Mercado\Repository\Pagamento\PagamentoRepository;
use Modules\Mercado\UseCases\Gerenciamento\Pagamento\Requests\CriarFormaPagamentoRequest;
use Modules\Mercado\UseCases\Gerenciamento\Pagamento\Requests\EditarFormaPagamentoRequest;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class FormaPagamentoController extends ControllerBaseMercado
{
    public function index()
    {
        return view('mercado::gerenciamento.forma_pagamento.index');
    }


    public function create()
    {
        $loja = auth()->user()->usuarioMercado->loja;
        $especies = EspeciePagamento::where('id', '!=', config('config.especie_pagamento.dinheiro.id'))->get();

        return view('mercado::gerenciamento.forma_pagamento.create', ['loja' => $loja, 'especies' => $especies]);
    }

    public function store(Request $request)
    {
        $this->getBd()->begin();

        try {
            $parans = (object) Post::anti_injection_array($request->all());

            $descricao = $parans->descricao;
            $especie = $parans->especie;
            $parcelas = $parans->parcelas;
            $ativo = $request->ativo && $request->ativo == 'on' ? true : false;
            $loja_id = auth()->user()->usuarioMercado->loja_id;
            $processo_id = config('config.processos.gerenciamento.forma_pagemento.id');
            $acao_id =  config('config.acoes.criou_forma_pagamento.id');
            $usuario_id = auth()->user()->usuarioMercado->id;

            $historico = new CriarHistoricoRequest($processo_id, $acao_id,$usuario_id);

            $criarFormaRequest = new CriarFormaPagamentoRequest($descricao, $ativo, $parcelas, $especie, $loja_id, $historico);

            $formaPagamento = PagamentoApplication::criarFormaPagamento($criarFormaRequest);
            $this->getBd()->commit();
            session()->flash('success', 'Forma de pagamento criada com sucesso.');
            return redirect()->route('cadastro.forma_pagemento.index');
        } catch (\Exception $e) {
            $this->getBd()->rollBack();
            session()->flash('error', 'Erro ao criar forma de pagamento!. Erro: ' . $e->getMessage());
            Log::error($e);
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $pagamento = PagamentoRepository::getFormaPagamentoById($id);
        $loja = auth()->user()->usuarioMercado->loja;
        $especies = EspeciePagamento::where('id', '!=', config('config.especie_pagamento.dinheiro.id'))->get();

        return view('mercado::gerenciamento.forma_pagamento.edit', ['pagamento' => $pagamento, 'loja' => $loja, 'especies' => $especies]);
    }

    public function update(Request $request, $id)
    {
        $this->getBd()->begin();

        try {
            $parans = (object) Post::anti_injection_array($request->all());
            $descricao = $parans->descricao;
            $formaPagamento = PagamentoRepository::getFormaPagamentoById($id);
            $ativo = $request->ativo && $request->ativo == 'on' ? true : false;
            $loja_id = auth()->user()->usuarioMercado->loja_id;
            $processo_id = config('config.processos.gerenciamento.forma_pagemento.id');
            $acao_id =  config('config.acoes.atualizou_forma_pagamento.id');
            $usuario_id = auth()->user()->usuarioMercado->id;

            $historico = new CriarHistoricoRequest($processo_id, $acao_id, $usuario_id);
            $criarFormaRequest = new CriarFormaPagamentoRequest($descricao, $ativo, $formaPagamento->parcelas, $formaPagamento->especie_pagamento_id, $loja_id, $historico);
            $editarFormaPagamentoRequest = new EditarFormaPagamentoRequest($id, $criarFormaRequest);

            $pagamento = PagamentoApplication::editarFormaPagamento($editarFormaPagamentoRequest);
            $this->getBd()->commit();

            session()->flash('success', 'Forma de pagamento atualizada com sucesso.');
            return redirect()->route('cadastro.forma_pagemento.index');
        } catch (\Exception $e) {
            $this->getBd()->rollBack();

            session()->flash('error', 'Erro ao atualizar forma de pagamento!. Erro: ' . $e->getMessage());
            Log::error($e);
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        //
    }
}
