<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Produto;

use Illuminate\Database\Eloquent\Model;
use Modules\Mercado\Application\CriarHistoricoApplication;
use Modules\Mercado\Application\EstoqueApplication;
use Modules\Mercado\Entities\Produto;
use Modules\Mercado\Repository\Produto\ProdutoRepository;
use Modules\Mercado\UseCases\Gerenciamento\Estoque\Requests\CriarEstoqueRequest;
use Modules\Mercado\UseCases\Gerenciamento\Produto\Requests\CriarProdutoRequest;

class CriarProduto
{
    private CriarProdutoRequest $request;

    public function __construct(CriarProdutoRequest $request)
    {
        $this->request = $request;
    }

    // Getters and Setters
    public function handle()
    {
        $produto = self::criaProduto();
        self::criaEstoque($produto);
        return $produto;
    }

    private function criaProduto()
    {
        $produto = ProdutoRepository::create(
            $this->request->getNome(),
            $this->request->getDescricao(),
            $this->request->getCodBarras(),
            $this->request->getCodAux(),
            $this->request->getUnidadeMedida(),
            $this->request->getClassificacaoId(),
            $this->request->getDataValidade(),
            $this->request->getFabrcanteId(),
            $this->request->getCriarHistoricoRequest()
        );

        return $produto;
    }

    private function criaEstoque(Produto $produto)
    {
        $quantidade_total = 0;
        $quantidade_disponivel = 0;
        $quantidade_minima = 0;
        $quantidade_maxima = 0;
        $localizacao = null;
        $estoques = [];

        foreach ($this->request->getLojas() as $key => $loja_id) {
            $estoque = EstoqueApplication::criarEstoque(new CriarEstoqueRequest(
                $this->request->getPrecoCusto(),
                $this->request->getPrecoVenda(),
                $produto->id,
                $loja_id,
                $quantidade_total,
                $quantidade_disponivel,
                $quantidade_minima,
                $quantidade_maxima,
                $localizacao,
                $this->request->getCriarHistoricoRequest()
            ));

            $estoques[] = $estoque;
        }

        return $estoques;
    }
}
