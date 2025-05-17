<?php

namespace Modules\Mercado\Http\Controllers\Estoque;

use App\System\Post;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Mercado\Application\BalancoApplication;
use Modules\Mercado\Http\Controllers\ControllerBaseMercado;
use Modules\Mercado\Repository\Balanco\BalancoRepository;
use Modules\Mercado\Repository\Estoque\EstoqueRepository;
use Modules\Mercado\Repository\Produto\ProdutoRepository;
use Modules\Mercado\UseCases\Gerenciamento\Balanco\Requests\BalancoItemRequest;
use Modules\Mercado\UseCases\Gerenciamento\Balanco\Requests\BalancoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class BalancoController extends ControllerBaseMercado
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $loja_id = auth()->user()->usuarioMercado->loja_id;
        $balancos = BalancoApplication::getTodosBalancos($loja_id);
        return view('mercado::estoque.balanco.index', ['balancos' => $balancos]);
    }

    public function getProdutos(Request $request)
    {
        $q = Post::anti_injection($request->q);
        $produtos = ProdutoRepository::getProdutoByNomeAndCodAux($q);

        $select = $produtos->map(function ($item) {
            return [
                'id' => $item->estoque_id,
                'text' => $item->nome . ' - ' . $item->sigla . ' - ' . $item->fabricante_nome
            ];
        });

        return response()->json($select, 200);
    }

    public function getEstoque(Request $request)
    {
        $estoque = EstoqueRepository::getEstoqueById(Post::anti_injection($request->id));
        return response()->json(['estoque' => $estoque], 200);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request)
    {
        // $this->getDb()->begin();

        try {
            $usuario = auth()->user()->usuarioMercado;
            $loja_id = $usuario->loja_id;
            // if ($balanco_em_aberto) {
            //     session()->flash('info', 'Balanço em aberto. Conclua o atual antes de iniciar um novo.');
            //     BalancoApplication::confereQuantidadeEstoqueItens($balanco_em_aberto->id);
            //     $this->getDb()->commit();

            //     return view('mercado::estoque.balanco.create', [
            //         'balanco' => $balanco_em_aberto
            //     ]);
            // }

            // $balanco = BalancoApplication::createBalanco(
            //     new BalancoRequest(
            //         $loja_id,
            //         config('config.status.aberto'),
            //         $usuario->id,
            //         $this->getCriarHistoricoRequest($request)
            //     )
            // );

            // $this->getDb()->commit();
            return view('mercado::estoque.balanco.create', ['balanco' => null]);
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        BalancoApplication::confereQuantidadeEstoqueItens($id);
        $balanco = BalancoRepository::getBalancoPorId($id);
        return view('mercado::estoque.balanco.edit', ['balanco' => $balanco]);
    }
    // public function detalhes(int $balanco_id)
    // {
    //     $balanco = BalancoApplication::getBalancoPorId($balanco_id);
    //     if (!$balanco) {
    //         session()->flash('warning', 'Balanço não encontrado!');
    //         return redirect()->route('home.index');
    //     }
    //     if ($balanco->status_id === config('config.status.concluido')) {
    //         return view(
    //             'mercado::estoque.balanco.detalhes',
    //             [
    //                 'balanco' => $balanco
    //             ]
    //         );
    //     }

    //     session()->flash('warning', 'Balanço não encontrado!');
    //     return redirect()->back();
    // }

    public function finalizar(Request $request)
    {
        $this->getDb()->begin();

        try {
            $parans = (object) Post::anti_injection_array($request->except('itens'));
            $itens = json_decode($request->itens);
            $historicoRequest = $this->getCriarHistoricoRequest($request);
            $serviceUseCase = new ServiceUseCase($historicoRequest);
            $finalizar = filter_var($request->finalizar, FILTER_VALIDATE_BOOLEAN);

            if (count($itens) == 0) {
                throw new Exception("Nenhum item a ser movimentado.", 1);
            }

            $balanco = $parans->balanco_id ? BalancoRepository::getBalancoPorId($parans->balanco_id) : null;
            if ($balanco) {
                //atualiza
                $historicoRequest->setAcaoId(config('config.acoes.atualiza_balanco_item.id'));
                $balanco = BalancoApplication::updateBalanco($balanco->id, new BalancoRequest(
                    auth()->user()->getUserModulo->loja_id,
                    config('config.status.aberto'),
                    auth()->user()->getUserModulo->id,
                    $parans->observacao,
                    $historicoRequest
                ));
            } elseif (!$balanco) {
                //cria
                $historicoRequest->setAcaoId(config('config.acoes.criou_balanco.id'));

                $balanco = BalancoApplication::createBalanco(
                    new BalancoRequest(
                        auth()->user()->getUserModulo->loja_id,
                        config('config.status.aberto'),
                        auth()->user()->getUserModulo->id,
                        $parans->observacao,
                        $historicoRequest
                    )
                );
            }

            $itensSeparados = $this->separaItensBalanco($itens, $balanco->id);
            $itensCriarEAtualizar = $itensSeparados['itens_lista'];
            $itensRemover = $itensSeparados['remover'];

            foreach ($itensCriarEAtualizar as $key => $value) {
                $balanco_item = BalancoApplication::createBalancoItem(
                    new BalancoItemRequest(
                        $value->estoqueId,
                        auth()->user()->getUserModulo->loja_id,
                        $value->quantidadeSistema,
                        $value->quantidadeReal,
                        $value->resultado,
                        $balanco->id,
                        0,
                        (int)$parans->tipo_movimentacao,
                        $historicoRequest
                    )
                );
            }

            foreach ($itensRemover as $key => $value) {
                BalancoApplication::deleteBalancoItem(
                    $value,
                    $historicoRequest
                );
            }

            //finaliza balanço
            if ($finalizar == true) {
                $historicoRequest->setAcaoId(config('config.acoes.finalizou_balanco.id'));
                $balanco = BalancoApplication::finalizarBalanco($serviceUseCase, $balanco->id, $parans->observacao);
                session()->flash('success', 'Movimentação finalizado com sucesso!');
            } else {
                session()->flash('success', 'Movimentação salva com sucesso!');
            }

            $this->getDb()->commit();
            return redirect()->route('estoque.balanco.index');
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            dd($e);
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        }
    }

    private function separaItensBalanco($itens, $balanco_id = null)
    {
        $response = ['itens_lista' => [], 'remover' => []];
        $balanco_itens = BalancoRepository::getBalancoItensPorBalancoId($balanco_id);

        if ($balanco_id == null || count($balanco_itens) == 0) {
            $response['itens_lista'] = $itens; //adiciona todos pois esta criando o balanco novo
        } else {
            // Itens já salvos no banco
            $itensExistentes = $balanco_itens->keyBy('estoque_id'); // ID único que relaciona os itens
            $idsUsados = [];
            foreach ($itens as $item) {
                $response['itens_lista'][] = $item;
                $idsUsados[] = $item->estoqueId;
            }

            // Os que estavam no banco mas não vieram do front → remover
            foreach ($itensExistentes as $estoqueId => $itemBanco) {
                if (!in_array($estoqueId, $idsUsados)) {
                    $response['remover'][] = $itemBanco->id;
                }
            }
        }
        return $response;
    }
    public function delete(Request $request, int $balanco_id)
    {
        $this->getDb()->begin();

        try {

            $balanco = BalancoApplication::cancelarBalanco(
                $balanco_id,
                $this->getCriarHistoricoRequest($request)
            );

            $this->getDb()->commit();

            session()->flash('success', 'Balanço cancelado com sucesso.');
            return redirect()->route('estoque.balanco.index');
        } catch (\Exception $e) {
            dd($e);
            $this->getDb()->rollBack();
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        }
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

            $params = (object) Post::anti_injection_array($request->all());

            $balanco_item = BalancoApplication::createBalancoItem(
                new BalancoItemRequest(
                    $params->estoque_id,
                    auth()->user()->getUserModulo->loja_id,
                    formatarQtdRequest($params->quantidade_estoque_sistema),
                    formatarQtdRequest($params->quantidade_estoque_real),
                    formatarQtdRequest($params->quantidade_operacional),
                    $params->balanco_id,
                    0,
                    (int)$params->tipo_movimentacao,
                    $this->getCriarHistoricoRequest($request)
                )
            );

            $this->getDb()->commit();
            return response()->json([
                'success' => true,
                'message' => 'Adicionado com sucesso!'
            ]);
        } catch (\Exception $e) {
            $this->getDb()->rollBack();

            return response()->json(['success' => false, 'message' =>  $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
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
}
