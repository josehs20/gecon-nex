<?php

namespace Modules\Mercado\UseCases\Pedido\Pedido;

use Exception;
use Modules\Mercado\Repository\Estoque\EstoqueRepository;
use Modules\Mercado\Repository\Pedido\PedidoRepository;
use Modules\Mercado\UseCases\Pedido\Pedido\Requests\CriarPedidoRequest;

class AtualizaPedido
{
    private int $id;
    private CriarPedidoRequest $request;

    public function __construct(int $id, CriarPedidoRequest $request)
    {
        $this->id = $id;
        $this->request = $request;
    }

    public function handle()
    {
        $this->validade();
        $pedido = $this->atualizaPedido();
        $this->atualizaPedidoItens($pedido);
        return $pedido;
    }

    // Validação dos dados
    private function validade()
    {
        if (count($this->request->getMateriais()) == 0) {
            throw new Exception("Não contém materiais a serem pedido", 1);
        }
        $pedido = PedidoRepository::getPedidoById($this->id);
        if ($pedido->status_id == config('config.status.em_cotacao')) {
            throw new Exception("Pedido já se encontra em cotação.", 1);
        }
        // Adicione outras validações específicas
    }

    // atualiza do pedido
    private function atualizaPedido()
    {
        return PedidoRepository::atualiza(
            $this->id,
            $this->request->getCriarHistoricoRequest(),
            $this->request->getLojaId(),
            $this->request->getStatusId(),
            $this->request->getCriarHistoricoRequest()->getUsuarioId(),
            $this->request->getDataLimite(),
            $this->request->getObservacao()
        );
    }

    // Criação dos itens do pedido
    private function atualizaPedidoItens($pedido)
    {
        $materiaisRequest = collect($this->request->getMateriais());
        // Buscar todos os itens atuais do pedido
        $idsEstoquesRequest = $materiaisRequest->pluck('estoqueId')->toArray();

        $itensAtualizados = [];

        // Atualiza ou cria os itens da request
        foreach ($materiaisRequest as $item) {
            $estoque = EstoqueRepository::getEstoqueById($item['estoqueId']);
            $quantidade_pedida = converteDinheiroParaFloat($item['quantidade']);
            $pedidoItem = PedidoRepository::getPedidoItemPorPedidoEEstoque($pedido->id, $estoque->id);

            if (!$pedidoItem) {
                $itensAtualizados[] = PedidoRepository::criaPedidoItem(
                    $this->request->getCriarHistoricoRequest(),
                    $pedido->id,
                    $estoque->produto_id,
                    $estoque->id,
                    $this->request->getLojaId(),
                    $quantidade_pedida,
                    $pedido->status_id
                );
            } else {
                $itensAtualizados[] = PedidoRepository::atualizaPedidoItem(
                    $pedidoItem->id,
                    $this->request->getCriarHistoricoRequest(),
                    $pedido->id,
                    $estoque->produto_id,
                    $estoque->id,
                    $this->request->getLojaId(),
                    $quantidade_pedida,
                    $pedido->status_id
                );
            }
        }
        $itensAtuais = PedidoRepository::getItensDoPedido($pedido->id); // você precisa ter esse método no repositório

        // Exclui os itens que não vieram na nova lista
        foreach ($itensAtuais as $itemAtual) {

            if (!in_array($itemAtual->estoque_id, $idsEstoquesRequest)) {

                PedidoRepository::removeItemDoPedido($itemAtual->id, $this->request->getCriarHistoricoRequest());
            }
        }

        return $itensAtualizados;
    }
}
