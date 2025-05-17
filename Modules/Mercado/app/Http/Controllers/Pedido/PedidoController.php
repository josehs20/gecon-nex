<?php

namespace Modules\Mercado\Http\Controllers\Pedido;

use App\System\Post;
use Illuminate\Http\Request;
use Modules\Mercado\Application\PedidoApplication;
use Modules\Mercado\Http\Controllers\ControllerBaseMercado;
use Modules\Mercado\Repository\Pedido\PedidoRepository;
use Modules\Mercado\UseCases\Pedido\Pedido\Requests\CriarPedidoRequest;

class PedidoController extends ControllerBaseMercado
{
    public function index()
    {
        return view('mercado::pedido.pedido.index');
    }

    public function create()
    {

        return view('mercado::pedido.pedido.create', ['pedido' => false]);
    }

    public function store(Request $request)
    {
        $this->getDb()->begin();
        try {
            $parans = (object) Post::anti_injection_array($request->except('itens'));
            $materiais = Post::anti_injection_array(json_decode($request->itens, true));
            $historico = $this->getCriarHistoricoRequest($request);
            // $fornecedor_id = $parans->fornecedor_id;
            $loja_id = auth()->user()->getUserModulo->loja_id;
            $status_id = config('config.status.aberto');
            $data_limite = $request->data_limite;
            // $previsao_entrega = $parans->previsao_entrega;
            // $frete = $request->frete ? $parans->frete : null;
            $observacao = $request->observacao ? $parans->observacao : null;
            $finalizar = filter_var($request->finalizar, FILTER_VALIDATE_BOOLEAN);
            $itens = json_decode($request->itens);

            if (count($itens) == 0) {
                throw new \Exception("Nenhum item a ser movimentado.", 1);
            }

            $pedido = $request->pedido_id ? PedidoRepository::getPedidoById($parans->pedido_id) : null;

            if ($pedido) {

                $historico->setAcaoId(config('config.acoes.atualizou_pedido.id'));
                $pedido = PedidoApplication::atualizaPedido($pedido->id, new CriarPedidoRequest(
                    $historico,
                    $loja_id,
                    $status_id,
                    $data_limite,
                    $materiais,
                    $observacao
                ));
            } else {
                $pedido = PedidoApplication::criarPedido(new CriarPedidoRequest(
                    $historico,
                    $loja_id,
                    $status_id,
                    $data_limite,
                    $materiais,
                    $observacao
                ));
            }

            if ($finalizar == true) {

                $historico->setAcaoId(config('config.acoes.realizou_pedido.id'));
                $pedido = PedidoApplication::finalizarPedido($pedido->id, $historico);
            }
            session()->flash('success', 'Pedido atualizado com cesso.');

            $this->getDb()->commit();
            return redirect()->back();
        } catch (\Exception $e) {

            $this->getDb()->rollBack();
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function edit($pedido_id)
    {

        $pedido = PedidoRepository::getPedidoById($pedido_id);

        if (!$pedido) {
            session('warning', 'Pedido não encontrado');
            return redirect()->back();
        }


        $pedido->pedido_itens->each(function ($item) {
            $item->status->descricao = $item->status->descricao();
        });

        return view('mercado::pedido.pedido.edit', ['pedido' => $pedido]);
    }

    public function delete(Request $request, $pedido_id)
    {

        $this->getDb()->begin();
        try {
            $historico = $this->getCriarHistoricoRequest($request);
            $historico->setComentario(Post::anti_injection($request->motivo));
            $pedido = PedidoApplication::cancelarPedido($historico, $pedido_id);
            $this->getDb()->commit();
            session()->flash('success', 'Pedido cancelado com sucesso.');
            return redirect()->route('cadastro.pedido.index');
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        }
    }
}
