<?php

namespace App\UseCases\Dashboard\Views;

use App\UseCases\Dashboard\HelperRenderizarViews;
use Modules\Mercado\Repository\Pedido\PedidoRepository;

class RenderizarViewPedido extends HelperRenderizarViews
{
    private int $loja_id;

    public function __construct(int $loja_id) {
        $this->loja_id = $loja_id;
    }

    public function handle(): array
    {
        return [
            'view_pedido' => true,
            'listagem_pedidos' => $this->getPedidos(),
            'quantidade_pedidos_aguardando_cotacao' => $this->obterQuantidade($this->getPedidosAguardandoCotacao()),
            'quantidade_pedidos_cotados' => $this->obterQuantidade($this->getPedidosCotados()),
            'quantidade_pedidos_em_aberto' => $this->obterQuantidade($this->getPedidosEmAberto()),
            'quantidade_pedidos_em_cotacao' => $this->obterQuantidade($this->getPedidosEmCotacao()),
            'quantidade_pedidos_cancelados' => $this->obterQuantidade($this->getPedidosCancelados()),
            'quantidade_pedidos' => $this->getQuantidadeTotalPedidos(),
            'quantidade_pedidos_comprados' => $this->obterQuantidade($this->getPedidosComprados()),
            'porcentagens' => $this->getPorcentagens()
        ];
    }

    private function getPorcentagens(){
        return [
            'pedidos_aguardando_cotação' => $this->calcularPorcentagem($this->getQuantidadeTotalPedidos(), $this->obterQuantidade($this->getPedidosAguardandoCotacao())),
            'pedidos_cotados' => $this->calcularPorcentagem($this->getQuantidadeTotalPedidos(), $this->obterQuantidade($this->getPedidosCotados())),
            'pedidos_em_aberto' => $this->calcularPorcentagem($this->getQuantidadeTotalPedidos(), $this->obterQuantidade($this->getPedidosEmAberto())),
            'pedidos_em_cotação' => $this->calcularPorcentagem($this->getQuantidadeTotalPedidos(), $this->obterQuantidade($this->getPedidosEmCotacao())),
            'pedidos_cancelados' => $this->calcularPorcentagem($this->getQuantidadeTotalPedidos(), $this->obterQuantidade($this->getPedidosCancelados())),
            'pedidos_comprados' => $this->calcularPorcentagem($this->getQuantidadeTotalPedidos(), $this->obterQuantidade($this->getPedidosComprados())),
            'pedidos_sem_cotação' => $this->calcularPorcentagem($this->getQuantidadeTotalPedidos(), $this->obterQuantidade($this->getPedidosSemCotacao())),
            'pedidos_com_cotação' => $this->calcularPorcentagem($this->getQuantidadeTotalPedidos(), $this->obterQuantidade($this->getPedidosComCotacao()))
        ];
    }

    private function getQuantidadeTotalPedidos(){
        return $this->obterQuantidade($this->getPedidos());
    }

    private function getPedidos(){
        return PedidoRepository::getPedidos($this->loja_id);
    }

    private function getPedidosAguardandoCotacao(){
        return PedidoRepository::getPedidosAguardandoCotacao($this->loja_id);
    }

    private function getPedidosCotados(){
        return PedidoRepository::getPedidosCotados($this->loja_id);
    }

    private function getPedidosEmAberto(){
        return PedidoRepository::getPedidosEmAberto($this->loja_id);
    }

    private function getPedidosEmCotacao(){
        return PedidoRepository::getPedidosEmCotacao($this->loja_id);
    }

    private function getPedidosCancelados(){
        return PedidoRepository::getPedidosCancelados($this->loja_id);
    }

    private function getPedidosSemCotacao(){
        return PedidoRepository::getPedidosSemCotacao($this->loja_id);
    }

    private function getPedidosComCotacao(){
        return PedidoRepository::getPedidosComCotacao($this->loja_id);
    }

    private function getPedidosComprados(){
        return PedidoRepository::getPedidosComprados($this->loja_id);
    }
}
