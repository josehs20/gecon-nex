<?php

namespace Modules\Mercado\Http\Controllers\Pedido;

use App\System\Post;
use Exception;
use Illuminate\Http\Request;
use Modules\Mercado\Application\CompraApplication;
use Modules\Mercado\Http\Controllers\ControllerBaseMercado;
use Modules\Mercado\Repository\Compra\CompraRepository;
use Modules\Mercado\Repository\Cotacao\CotacaoRepository;
use Modules\Mercado\Repository\Cotacao\CotFornecedorRepository;
use Modules\Mercado\UseCases\Pedido\Compra\Requests\CriarCompraRequest;

class CompraController extends ControllerBaseMercado
{
    public function index()
    {
        $status = [
            config('config.status.cotado')
        ];
        $cotacoes_cotadas = CotacaoRepository::getCotacoesBysatus(auth()->user()->getUserModulo->loja_id, $status);
        $cotacoes_cotadas = $cotacoes_cotadas->count();
        return view('mercado::pedido.compra.index', compact('cotacoes_cotadas'));
    }

    private function juntacotForItens($cotacao)
    {
        return $cotacao->cot_fornecedores->transform(function ($fornecedor) {
            $itensProcessados = $fornecedor->cot_for_itens
                ->groupBy('estoque_id')
                ->map(function ($items) {
                    $base = $items->first();
                    $base->quantidade = $items->sum('quantidade');

                    $pedidos = $items->pluck('pedido_id')->filter()->unique();
                    $base->pedidos_agrupados = $pedidos->isNotEmpty() ? $pedidos->join('/') : null;

                    return $base;
                })
                ->filter(function ($item) {
                    return !empty($item->pedidos_agrupados);
                })
                ->values();

            $fornecedor->setRelation('cot_for_itens', $itensProcessados);

            return $fornecedor;
        });
    }
    public function selecionar_cotacoes()
    {
        $status = [
            config('config.status.cotado')
        ];
        $cotacoes_aguardando_compra = CotacaoRepository::getCotacoesBysatus(auth()->user()->getUserModulo->loja_id, $status);
        $cotacoes_aguardando_compra->transform(function ($cotacao) {
            $this->juntacotForItens($cotacao); //ele altera o objeto dentro da funcao
            return $cotacao;
        });

        return view('mercado::pedido.compra.selecionar_cotacoes', ['cotacoes_aguardando_compra' => $cotacoes_aguardando_compra]);
    }

    public function create($cotacao_id)
    {
        $cotacao = CotacaoRepository::getCotacaoById($cotacao_id);
        if (!$cotacao) {
            session()->flash('error', 'Cotação não encontrada.');
            return redirect()->back();
        }
        if ($cotacao->loja_id != auth()->user()->getUserModulo->loja_id) {
            session()->flash('warning', 'Essa contação não pertence a loja em que você está.');
            return redirect()->back();
        }
        $this->juntacotForItens($cotacao);

        $forma_pagamentos = config('config.especie_pagamento');
        $forma_pagamentos = [
            $forma_pagamentos['pix'],
            $forma_pagamentos['cartao_debito'],
            $forma_pagamentos['cartao_credito'],
            $forma_pagamentos['boleto'],
            $forma_pagamentos['transferencia'],
        ];

        return view('mercado::pedido.compra.create', ['podeAlterar' => true, 'cotacao' => $cotacao, 'forma_pagamentos' => $forma_pagamentos]);
    }

    public function store(Request $request)
    {
        $this->getDb()->begin();
        try {

            $parans = (object) Post::anti_injection_array($request->all());
            $historico = $this->getCriarHistoricoRequest($request);
            $cot_fornecedor = CotFornecedorRepository::getCotFornecedorById($parans->cot_fornecedor_id);
            if (!$cot_fornecedor) {
                throw new Exception("Cotação desse fornecedor não encontrada.", 1);

            }

            $status = config('config.status.comprado');
            $criarCompraRequest = new CriarCompraRequest(
                $cot_fornecedor->loja_id,
                $historico->getUsuarioId(),
                $cot_fornecedor->cotacao_id,
                $cot_fornecedor->id,
                $status,
                $parans->especie_pagamento_id,
                $historico
            );

            $compra = CompraApplication::criarCompra($criarCompraRequest);

            session()->flash('success', 'Compra realizada com sucesso.');

            $this->getDb()->commit();
            return redirect()->route('cadastro.compra.index');
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function edit($compra_id)
    {
        $compra = CompraRepository::getCompraById($compra_id);
        if (!$compra) {
            session()->flash('error', 'Compra não encontrada.');
            return redirect()->back();
        }

        if (!$compra->cotacao) {
            session()->flash('error', 'Cotação não encontrada.');
            return redirect()->back();
        }

        if ($compra->cotacao->loja_id != auth()->user()->getUserModulo->loja_id) {
            session()->flash('warning', 'Essa contação não pertence a loja em que você está.');
            return redirect()->back();
        }

        $this->juntacotForItens($compra->cotacao);

        return view('mercado::pedido.compra.edit', ['podeAlterar' => false,'compra' => $compra, 'cotacao' => $compra->cotacao]);
    }

    public function delete(Request $request, $compra_id)
    {
        $this->getDb()->begin();
        try {
            $historico = $this->getCriarHistoricoRequest($request);
            $historico->setComentario(Post::anti_injection($request->motivo));
            $compra = CompraApplication::cancelarCompra($historico, $compra_id);

            $this->getDb()->commit();
            session()->flash('success', 'Compra cancelada com sucesso.');
            return redirect()->route('cadastro.compra.index');
        } catch (\Exception $e) {
         
            $this->getDb()->rollBack();
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        }
    }
}
