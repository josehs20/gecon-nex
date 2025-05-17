<?php

namespace Modules\Mercado\Http\Controllers\Estoque;

use App\System\Post;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Mercado\Application\MovimentacaoEstoqueApplication;
use Modules\Mercado\Entities\MovimentacaoEstoqueItem;
use Modules\Mercado\Entities\TipoMovimentacaoEstoque;
use Modules\Mercado\Http\Controllers\ControllerBaseMercado;
use Modules\Mercado\Repository\Estoque\EstoqueRepository;
use Modules\Mercado\Repository\MovimentacaoEstoque\MovimentacaoEstoqueRepository;
use Modules\Mercado\Repository\Produto\ProdutoRepository;
use Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque\Requests\MovimentacaoEstoqueItemRequest;
use Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque\Requests\MovimentacaoEstoqueRequest;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class MovimentacaoController extends ControllerBaseMercado
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        // $lojaId = auth()->user()->usuarioMercado->loja_id;
        // $movimentacoes = MovimentacaoEstoqueApplication::getTodasMovimentacoes($lojaId);

        return view('mercado::estoque.movimentacoes.index');
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

    public function movimentar(Request $request)
    {
        $this->getDb()->begin();

        try {
            $parans = (object) Post::anti_injection_array($request->all());
            $quantidade = formatarQtdRequest($parans->quantidade);

            $movimentacao_estoque_item = MovimentacaoEstoqueApplication::movimentar(new MovimentacaoEstoqueItemRequest(
                $parans->estoque_id,
                $parans->movimentacao_id,
                $parans->tipo_movimentacao,
                $quantidade,
                $this->getCriarHistoricoRequest($request)
            ));

            $this->getDb()->commit();

            return response()->json([
                'success' => true,
                'message' => 'Adicionado com sucesso!',
            ]);
        } catch (Exception $e) {
            // dd($e);
            $this->getDb()->rollBack();
            return response()->json(['success' => false, 'message' =>  $e->getMessage()]);
        }
    }

    public function delete(Request $request, $movimentacao_id)
    {
        $this->getDb()->begin();

        try {
            $movimentacao = MovimentacaoEstoqueApplication::cancelarMovimentacao($movimentacao_id, $this->getCriarHistoricoRequest($request));


            $this->getDb()->commit();
            session()->flash('success', 'Movimentação cancelada com sucesso.');
            // Retorna resposta JSON de sucesso
            return redirect()->route('estoque.movimentacao.index');
        } catch (Exception $e) {
            $this->getDb()->rollBack();
            session()->flash('error', $e->getMessage());

            // Retorna resposta JSON de erro
            return redirect()->back();
        }
    }

    /**
     * Abre a tela para criar as movimentações de estoque.
     * Ao clicar no botao nova movimentação, abre a tela e também cria a MovimentacaoEstoque.
     * Só pode uma movimentação por vez
     * @return Renderable
     */
    public function create(Request $request)
    {
        // $this->getDb()->begin();

        try {
            $usuario = auth()->user()->getUserModulo;
            // $movimentacaoEstoqueEmAberto = MovimentacaoEstoqueApplication::verificarExisteciaDeMovimentacaoEstoqueEmAberto($usuario->loja_id);
            $tipoMovimentacoes = TipoMovimentacaoEstoque::whereIn('id', [config('config.tipo_movimentacao_estoque.entrada'), config('config.tipo_movimentacao_estoque.saida')])
                ->get()->map(function ($item) {
                    $item->descricao = $item->descricao . ' ' . ($item->id == config('config.tipo_movimentacao_estoque.entrada') ? '+' : '-');
                    return $item;
                });

            // if ($movimentacaoEstoqueEmAberto) {
            //     return view('mercado::estoque.movimentacoes.create', [
            //         'movimentacao' => $movimentacaoEstoqueEmAberto,
            //         'tipoMovimentacoes' => $tipoMovimentacoes
            //     ]);
            // }

            // $historicoRequest = $this->getCriarHistoricoRequest($request);

            // $movimentacao = MovimentacaoEstoqueApplication::criarMovimentacaoEstoque(
            //     new MovimentacaoEstoqueRequest(
            //         $usuario->loja_id,
            //         config('config.status.aberto'),
            //         $usuario->id,
            //         config('config.tipo_movimentacao_estoque.movimentacao'),
            //         $historicoRequest
            //     )
            // );

            // $this->getDb()->commit();

            return view('mercado::estoque.movimentacoes.create', [
                'movimentacao' => null,
                'tipoMovimentacoes' => $tipoMovimentacoes
            ]);
        } catch (Exception $e) {
            // $this->getDb()->rollBack();

            session()->flash('error', $e->getMessage());

            return redirect()->back();
        }
    }

    public function detalhesMovimentacao(int $movimentacaoId)
    {
        $movimentacao_estoque = MovimentacaoEstoqueApplication::getMovimentacaoEstoquePorId($movimentacaoId);
        if ($movimentacao_estoque->status_id === config('config.status.concluido')) {
            return view('mercado::estoque.movimentacoes.detalhes', [
                'movimentacao' => $movimentacao_estoque,
            ]);
        } else {
            $tipoMovimentacoes = MovimentacaoEstoqueRepository::getTipoMovimentacoes()->whereIn('id', [config('config.tipo_movimentacao_estoque.entrada'), config('config.tipo_movimentacao_estoque.saida')])->map(function ($item) {
                $item->descricao = $item->descricao . ' ' . ($item->id == config('config.tipo_movimentacao_estoque.entrada') ? '+' : '-');
                return $item;
            });
            return view('mercado::estoque.movimentacoes.create', [
                'movimentacoesItem' => $movimentacao_estoque->movimentacao_estoque_itens,
                'movimentacao' => $movimentacao_estoque,
                'tipoMovimentacoes' => $tipoMovimentacoes
            ]);
        }
    }

    public function finalizar_movimentacao(Request $request)
    {
        $this->getDb()->begin();

        try {
            $historicoRequest = $this->getCriarHistoricoRequest($request);
            $finalizar = filter_var($request->finalizar, FILTER_VALIDATE_BOOLEAN);
            $usuario = auth()->user()->getUserModulo;
            $parans = (object) Post::anti_injection_array($request->except('itens'));
            $itens = json_decode($request->itens);

            if (count($itens) == 0) {
                throw new Exception("Nenhum item a ser movimentado.", 1);
            }

            $movimentacao = $request->movimentacao_id ? MovimentacaoEstoqueRepository::getMovimentacaoEstoquePorId($parans->movimentacao_id) : null;

            if ($movimentacao) {

                $historicoRequest->setAcaoId(config('config.acoes.atualizou_movimentacao_estoque.id'));
                $movimentacao = MovimentacaoEstoqueApplication::atualizaMovimentacaoEstoque(
                    $movimentacao->id,
                    new MovimentacaoEstoqueRequest(
                        $usuario->loja_id,
                        config('config.status.aberto'),
                        $usuario->id,
                        config('config.tipo_movimentacao_estoque.movimentacao'),
                        $historicoRequest,
                        $parans->observacao
                    )
                );
            } else {
                $historicoRequest->setAcaoId(config('config.acoes.criou_movimentacao_estoque.id'));
                $movimentacao = MovimentacaoEstoqueApplication::criarMovimentacaoEstoque(
                    new MovimentacaoEstoqueRequest(
                        $usuario->loja_id,
                        config('config.status.aberto'),
                        $usuario->id,
                        config('config.tipo_movimentacao_estoque.movimentacao'),
                        $historicoRequest,
                        $parans->observacao
                    )
                );
            }
            $itensSeparados = $this->separaItensMovimentacao($itens, $movimentacao->id);
            $itensCriarEAtualizar = $itensSeparados['itens_lista'];
            $itensRemover = $itensSeparados['remover'];

            foreach ($itensCriarEAtualizar as $key => $value) {
                $movimentacao_estoque_item = MovimentacaoEstoqueApplication::movimentar(new MovimentacaoEstoqueItemRequest(
                    $value->estoqueId,
                    $movimentacao->id,
                    $value->tipoMovimentacao,
                    $value->quantidadeMovimentar,
                    $this->getCriarHistoricoRequest($request)
                ));
            }
            foreach ($itensRemover as $key => $value) {
                MovimentacaoEstoqueApplication::deletarMovimentacaoEstoqueItem(
                    $value,
                    $historicoRequest
                );
            }

            if ($finalizar == true) {
                $historicoRequest->setAcaoId(config('config.acoes.finalizou_movimentacao.id'));
                $movimentacao = MovimentacaoEstoqueApplication::finalizarMovimentacao($request->movimentacao_id, $historicoRequest);
                session()->flash('success', 'Movimentação concluída com sucesso.');
            }else {
                session()->flash('success', 'Movimentação salva com sucesso.');
            }
            $this->getDb()->commit();
            return redirect()->route('estoque.movimentacao.index');
        } catch (\Exception $e) {

            $this->getDb()->rollBack();
            session()->flash('error', $e->getMessage());
            session()->flash('itens_na_movimentacao', json_decode($request->itens));
            return redirect()->back();
        }
    }
    private function separaItensMovimentacao($itens, $movimentacao_id = null)
    {
        $response = ['itens_lista' => [], 'remover' => []];
        $movimentacao_estoque_itens = MovimentacaoEstoqueRepository::getMovimentacoesItemPorMovimentacaoId($movimentacao_id);

        if ($movimentacao_id == null || count($movimentacao_estoque_itens) == 0) {
            $response['itens_lista'] = $itens; //adiciona todos pois esta criando o balanco novo
        } else {
            // Itens já salvos no banco
            $itensExistentes = $movimentacao_estoque_itens->keyBy('estoque_id'); // ID único que relaciona os itens
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
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('mercado::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        // BalancoApplication::confereQuantidadeEstoqueItens($id);
        $movimentacao = MovimentacaoEstoqueRepository::getMovimentacaoEstoquePorId($id);
        return view('mercado::estoque.movimentacoes.edit', ['movimentacao' => $movimentacao]);
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
