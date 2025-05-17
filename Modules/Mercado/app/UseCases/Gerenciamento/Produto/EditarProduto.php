<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Produto;

use Modules\Mercado\Application\EstoqueApplication;
use Modules\Mercado\Entities\Produto;
use Modules\Mercado\Repository\Estoque\EstoqueRepository;
use Modules\Mercado\Repository\Produto\ProdutoRepository;
use Modules\Mercado\UseCases\Gerenciamento\Estoque\Requests\CriarEstoqueRequest;
use Modules\Mercado\UseCases\Gerenciamento\Produto\Requests\EditarProdutoRequest;

class EditarProduto
{
    private EditarProdutoRequest $request;

    public function __construct(EditarProdutoRequest $request)
    {
        $this->request = $request;
    }

    // Getters and Setters
    public function handle()
    {
        $produto = $this->editarProduto();
        $this->criaProdutoLojas($produto);
        return $produto;
    }

    private function editarProduto()
    {
        $produto = ProdutoRepository::editar(
            $this->request->getId(),
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

        $this->atualizaQuantidades($produto);
        return $produto;
    }

    private function criaProdutoLojas(Produto $produto)
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
    }

    private function atualizaQuantidades(Produto $produto)
    {
        $estoques = $produto->estoques;
        foreach ($estoques as $key => $e) {
            EstoqueRepository::updatePrecos(
                $e->id,
                $this->request->getPrecoCusto(),
                $this->request->getPrecoVenda(),
                $this->request->getCriarHistoricoRequest()
            );
        }
    }
}
