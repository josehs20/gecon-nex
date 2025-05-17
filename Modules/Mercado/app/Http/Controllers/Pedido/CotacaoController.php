<?php

namespace Modules\Mercado\Http\Controllers\Pedido;

use App\System\Post;
use Modules\Mercado\Http\Controllers\ControllerBaseMercado;
use Illuminate\Http\Request;
use Modules\Mercado\Application\CotacaoApplication;
use Modules\Mercado\Entities\Cotacao;
use Modules\Mercado\Entities\CotacaoFornecedor;
use Modules\Mercado\Entities\CotacaoFornecedorItem;
use Modules\Mercado\Repository\Cotacao\CotacaoRepository;
use Modules\Mercado\Repository\Fornecedor\FornecedorRepository;
use Modules\Mercado\Repository\Pedido\PedidoRepository;

class CotacaoController extends ControllerBaseMercado
{
    public function index()
    {
        $status = [
            config('config.status.aguardando_cotacao')
        ];
        $aguardando_cotacao = PedidoRepository::getPedidosPorStatus(auth()->user()->getUserModulo->loja_id, $status);
        $aguardando_cotacao = $aguardando_cotacao->map(function ($item) {
            return $item->pedido_itens->count();
        })->sum();

        return view('mercado::pedido.cotacao.index', compact('aguardando_cotacao'));
    }

    public function create(Request $request)
    {
        try {
            $fornecedores_ids = json_decode($request->fornecedores);
            $pedidos_ids = json_decode($request->pedidos);

            if (!$request->pedidos) {
                throw new \Exception("Nenhum pedido selecionado.", 1);
            }
            if (!$request->fornecedores) {
                throw new \Exception("Nenhum fornecedor selecionado.", 1);
            }
            $fornecedores = FornecedorRepository::getFornecedoresById($fornecedores_ids);
            $pedidos = PedidoRepository::getPedidosById($pedidos_ids);
            //validar se pedidos já foram cotados

            //formata os fornecedores para que cada um tenha todos os itens do pedido
            $cotacao = new Cotacao([
                'loja_id' => auth()->user()->getUserModulo->loja_id,
                'status_id' => config('config.status.aguardando_cotacao'),
                'usuario_id' => auth()->user()->getUserModulo->id,
                // 'descricao' => null,
                // 'data_abertura' => 'Aguardando cotação'
            ]);
            $cotacao->cot_fornecedores = $fornecedores->map(function ($fornecedor) use ($pedidos, $cotacao) {
                $cot_fornecedor = new CotacaoFornecedor([
                    'loja_id' => $cotacao->loja_id,
                    'fornecedor_id' => $fornecedor->id,
                    'total' => null,
                    'frete' => null,
                    'observacao' => null,
                    'previsao_entrega' => null
                ]);
                $cot_fornecedor->cot_for_itens = $pedidos->map(function ($p) use ($fornecedor) {
                    return $p->pedido_itens->map(function ($pi) use ($fornecedor) {
                        return new CotacaoFornecedorItem([
                            'fornecedor_id' => $fornecedor->id,
                            'pedido_item_id' => $pi->id,
                            // 'cotacao_id',
                            'loja_id' => $pi->loja_id,
                            'estoque_id' => $pi->estoque_id,
                            'produto_id' => $pi->produto_id,
                            'status_id' => $pi->status_id,
                            'quantidade' => $pi->quantidade_pedida,
                            'preco_unitario' => null, // ajustar se necessário
                        ]);
                    });
                })->flatten()
                    ->groupBy('produto_id')->map(function ($items) {
                        // Pega o primeiro item como base e soma as quantidades
                        $base = $items->first();
                        $base->quantidade = $items->sum('quantidade');
                        $base->pedidos_agrupados = $items->map(function ($item) {

                            return $item->pedidoItem->pedido_id;
                        })->filter()->unique()->join(' / ');
                        return $base;
                    })
                    ->values(); // Se quiser resetar as chaves

                return $cot_fornecedor;
            });

            return view('mercado::pedido.cotacao.create', ['cotacao' => $cotacao]);
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function edit($cotacao_id)
    {
        try {
            $cotacao = CotacaoRepository::getCotacaoById($cotacao_id);

            $cotacao->cot_fornecedores->transform(function ($fornecedor) {
                $fornecedor->cot_for_itens = $fornecedor->cot_for_itens
                    ->groupBy('estoque_id')
                    ->map(function ($items) {
                        $base = $items->first();
                        $base->quantidade = $items->sum('quantidade');
                        $base->pedidos_agrupados = $items->map(function ($item) {
                            return $item->pedido_id;
                        })->filter()->unique()->join(' / ');
                        return $base;
                    })
                    ->values(); // Converte de Collection agrupada para indexada sequencialmente

                return $fornecedor;
            });

            return view('mercado::pedido.cotacao.edit', ['cotacao' => $cotacao]);
        } catch (\Exception $e) {
            // dd($e);
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function selecionarPedidos()
    {
        $pedidos_aguardando_cotacao = PedidoRepository::getPedidosPorStatus(auth()->user()->getUserModulo->loja_id, [
            config('config.status.aguardando_cotacao')
        ]);

        return view('mercado::pedido.cotacao.selecionar_pedidos', ['pedidos_aguardando_cotacao' => $pedidos_aguardando_cotacao]);
    }

    public function store(Request $request)
    {
        $this->getDb()->begin();
        try {
            $pedidos = json_decode($request->pedidos);
            $fornecedores = json_decode($request->fornecedores);
            /**
             * Executa
             */
            $cotacao = CotacaoApplication::inicicarCotacao($pedidos, $fornecedores, $this->getCriarHistoricoRequest($request));
            $this->getDb()->commit();
            session()->flash('success', 'Cotação iniciada com sucesso.');
            return redirect()->route('cadastro.cotacao.edit', ['cotacao_id' => $cotacao->id]);
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        $this->getDb()->begin();
        try {
            $parans = Post::anti_injection_array($request->all());
            $finalizar = filter_var($parans['finalizar'], FILTER_VALIDATE_BOOLEAN);
            $parans['finalizar'] = $finalizar;
            /**
             * Executa
             */
            //sempre atualiza com o que tem

            $cotacao = CotacaoApplication::atualizarCotacao($parans, $this->getCriarHistoricoRequest($request));
            if ($finalizar == true) {
                $msg = 'Cotação finalizada com sucesso';
            } else {
                $msg = 'Cotação atualizada com sucesso';
            }

            $this->getDb()->commit();
            $cotacao->status_badge = $cotacao->status->badge();
            $cotacao->status_descricao = $cotacao->status->descricao();
            return response()->json(['success' => true, 'msg' => $msg, 'cotacao' => $cotacao]);
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function delete(Request $request, $cotacao_id)
    {
        $this->getDb()->begin();
        try {
            $historico = $this->getCriarHistoricoRequest($request);
            $historico->setComentario(Post::anti_injection($request->motivo));
            $cotacao = CotacaoApplication::cancelarCotacao($historico, $cotacao_id);

            $this->getDb()->commit();
            session()->flash('success', 'Cotação cancelada com sucesso.');
            return redirect()->route('cadastro.cotacao.index');
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        }
    }
}
