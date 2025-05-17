<?php

namespace Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque;

use Modules\Mercado\Application\EstoqueApplication;
use Modules\Mercado\Repository\MovimentacaoEstoque\MovimentacaoEstoqueRepository;
use Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque\Requests\MovimentacaoEstoqueItemRequest;

class MovimentacaoEstoqueItem
{
    private MovimentacaoEstoqueItemRequest $request;
    public function __construct(MovimentacaoEstoqueItemRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $this->validade();
        $movimentacaoItem = $this->movimentar();
        return $movimentacaoItem;
    }

    public function movimentar()
    {
        $movimentacao_item = MovimentacaoEstoqueRepository::getMovimentacaoItemByEstoqueIdAndMovimentacao(
            $this->request->getMovimentacaoEstoqueId(),
            $this->request->getEstoqueId(),
            function($query){
                $query->withTrashed();
            }
        );
        if ($movimentacao_item) {
            return MovimentacaoEstoqueRepository::updateMovimentacaoEstoqueItem(
                $movimentacao_item->id,
                $this->request->getEstoqueId(),
                $this->request->getMovimentacaoEstoqueId(),
                $this->request->getTipoMovimentacao(),
                $this->request->getQuantidade(),
                0,
                $this->request->getCriarHistoricoRequest()
            );
        }

        return MovimentacaoEstoqueRepository::createMovimentacaoEstoqueItem(
            $this->request->getEstoqueId(),
            $this->request->getMovimentacaoEstoqueId(),
            $this->request->getTipoMovimentacao(),
            $this->request->getQuantidade(),
            0,
            $this->request->getCriarHistoricoRequest()
        );
    }

    private function validade()
    {
        $validarSaida = [
            config('config.tipo_movimentacao_estoque.venda'),
            config('config.tipo_movimentacao_estoque.saida')
        ];

        if (in_array($this->request->getTipoMovimentacao(), $validarSaida)) {
            $estoque = EstoqueApplication::getEstoqueById($this->request->getEstoqueId());
            //verifica se o estoque atual do item é permitido
            if ($estoque->quantidade_disponivel < $this->request->getQuantidade()) {
                throw new \Exception("A quantidade movimentada para saida é maior que a quantidade em estoque do materia " . $estoque->produto->getNomeCompleto(), 1);
            }
        }
    }
}
