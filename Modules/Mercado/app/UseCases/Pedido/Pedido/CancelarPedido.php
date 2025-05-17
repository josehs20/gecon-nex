<?php

namespace Modules\Mercado\UseCases\Pedido\Pedido;

use Exception;
use Modules\Mercado\Repository\Pedido\PedidoRepository;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class CancelarPedido
{
    private CriarHistoricoRequest $criarHistoricoRequest;
    private int $pedido_id;

    public function __construct(int $pedido_id,CriarHistoricoRequest $criarHistoricoRequest)
    {
        $this->criarHistoricoRequest = $criarHistoricoRequest;
        $this->pedido_id = $pedido_id;
    }

    public function handle()
    {
        $this->validade();
        $this->cancelaPedido();
        $this->cancelaPedidoItens();
        return;

    }

    // Validação dos dados
    private function validade()
    {
        $pedido = PedidoRepository::getPedidoById($this->pedido_id);
        if ($pedido->status_id == config('config.status.em_cotacao')) {
            throw new Exception("Pedido já se encontra em cotação.", 1);
        }
    }

    // Criação do pedido
    private function cancelaPedido()
    {
        return PedidoRepository::updateStatus(
            $this->criarHistoricoRequest,
            $this->pedido_id,
            config('config.status.cancelado')
        );
    }

    private function cancelaPedidoItens()
    {
        $pedido = PedidoRepository::getPedidoById($this->pedido_id);
        foreach ($pedido->pedido_itens as $key => $value) {
            PedidoRepository::atualizaStatusPedidoItem($value->id,$pedido->status_id, $this->criarHistoricoRequest);
        }
    }
}
