<?php

namespace Modules\Mercado\UseCases\Pedido\Pedido;

use Exception;
use Modules\Mercado\Repository\Estoque\EstoqueRepository;
use Modules\Mercado\Repository\Pedido\PedidoRepository;
use Modules\Mercado\UseCases\Pedido\Pedido\Requests\CriarPedidoRequest;

class CriarPedido
{
    private CriarPedidoRequest $request;

    public function __construct(CriarPedidoRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $this->validade();
        $pedido = $this->criaPedido();
        $this->criaPedidoItens($pedido);
        return $pedido;
    }

    // Validação dos dados
    private function validade()
    {
        if (count($this->request->getMateriais()) == 0) {
            throw new Exception("Não contém materiais a serem pedido", 1);
        }
        // Adicione outras validações específicas
    }

    // Criação do pedido
    private function criaPedido()
    {
        return PedidoRepository::create(
            $this->request->getCriarHistoricoRequest(),
            $this->request->getLojaId(),
            $this->request->getStatusId(),
            $this->request->getCriarHistoricoRequest()->getUsuarioId(),
            $this->request->getDataLimite(),
            $this->request->getObservacao()
        );
    }

    // Criação dos itens do pedido
    private function criaPedidoItens($pedido)
    {
        $itens = [];
        foreach ($this->request->getMateriais() as $item) {
            $estoque = EstoqueRepository::getEstoqueById($item['estoqueId']);
            $quantidade_pedida = converteDinheiroParaFloat($item['quantidade']);

            $itens[] = PedidoRepository::criaPedidoItem(
                $this->request->getCriarHistoricoRequest(),
                $pedido->id,
                $estoque->produto_id,
                $estoque->id,
                $this->request->getLojaId(),
                $quantidade_pedida,
                $this->request->getStatusId()
            );
        }
        return $itens;
    }
}
