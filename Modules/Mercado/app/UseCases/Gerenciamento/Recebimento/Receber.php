<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Recebimento;

use Modules\Mercado\Application\CompraApplication;
use Modules\Mercado\Application\EstoqueApplication;
use Modules\Mercado\Repository\Recebimento\RecebimentoRepository;
use Modules\Mercado\UseCases\Gerenciamento\Estoque\Requests\UpdateQtdDisponivelRequest;
use Modules\Mercado\UseCases\Gerenciamento\Estoque\UpdateQtdDisponivel;
use Modules\Mercado\UseCases\Gerenciamento\Recebimento\Requests\ReceberRequest;

class Receber
{
    private ReceberRequest $request;

    public function __construct(ReceberRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $recebimento = $this->receber();
        $this->receberItens($recebimento->id);
        $this->atualizarEstoque();
        return $recebimento;
    }

    private function receber(){
        return RecebimentoRepository::create(
            $this->request->getCriarHistoricoRequest(),
            $this->request->getCompraId(),
            $this->request->getCriarHistoricoRequest()->getUsuarioId(),
            $this->request->getCriarHistoricoRequest()->getLojaId(),
            $this->request->getStatusId(),
            $this->request->getDataRecebimento(),
            $this->request->getObservacao()
        );
    }

    private function receberItens(int $recebimento_id){
        $compra_itens = $this->getItensDaCompra();
        
        foreach ($compra_itens as $key => $compra_item) {
            RecebimentoRepository::createRecebimentoItem(
                $this->request->getCriarHistoricoRequest(),
                $recebimento_id,
                $this->request->getCriarHistoricoRequest()->getLojaId(),
                $compra_item->cotacaoFornecedorItem->produto_id,
                $compra_item->cotacaoFornecedorItem->estoque_id,
                $compra_item->id,
                $this->request->getStatusId(),
                $compra_item->cotacaoFornecedorItem->quantidade,
                $compra_item->cotacaoFornecedorItem->pedidoItem->quantidade_pedida,
                $compra_item->cotacaoFornecedorItem->preco_unitario,
                (float)($compra_item->cotacaoFornecedorItem->preco_unitario * (float)($compra_item->cotacaoFornecedorItem->quantidade))
            );
        }
    }

    private function obterCompra(){
        return CompraApplication::obterCompraPorId($this->request->getCompraId());
    }

    private function getItensDaCompra(){
        return $this->obterCompra()->compra_itens;
    }

    private function atualizarEstoque(){
        $compra_itens = $this->getItensDaCompra();
        
        foreach ($compra_itens as $key => $compra_item) {
            $produto_estoque = EstoqueApplication::getEstoqueById($compra_item->cotacaoFornecedorItem->estoque_id);
            if(!$produto_estoque){
                throw new \Exception("Item inexistente!", 400);
            }
           
            $novo_quantidade_disponivel = $produto_estoque->quantidade_disponivel + $compra_item->cotacaoFornecedorItem->quantidade;
            $novo_quantidade_total = $produto_estoque->quantidade_total + $compra_item->cotacaoFornecedorItem->quantidade;
            $estoque_id = $produto_estoque->id;

            EstoqueApplication::updateQtdDisponivel(
                new UpdateQtdDisponivelRequest(
                    $estoque_id,
                    $novo_quantidade_disponivel,
                    $novo_quantidade_total,
                    $this->request->getCriarHistoricoRequest()
                )
            );   
            
        }
    }
}
