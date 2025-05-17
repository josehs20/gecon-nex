<?php

namespace Modules\Mercado\UseCases\Pedido\Pedido;

use Modules\Mercado\Repository\Pedido\PedidoRepository;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class FinalizaPedido
{
    private CriarHistoricoRequest $criarHistoricoRequest;
    private int $pedido_id;

    public function __construct(int $pedido_id, CriarHistoricoRequest $criarHistoricoRequest)
    {
        $this->criarHistoricoRequest = $criarHistoricoRequest;
        $this->pedido_id = $pedido_id;
    }

    public function handle()
    {
        $this->validade();
        $this->mandaPedidoParaCotacao();
        $this->mandaItensParaCotacao();
        return;
    }

    // Validação dos dados
    private function validade()
    {
        $pedidoItens = PedidoRepository::getItensDoPedido($this->pedido_id);
        if ($pedidoItens->count() == 0) {
            throw new \Exception("Nenhum item no pedido para ser finalizado.", 1);
        }
    }

    // Criação do pedido
    private function mandaPedidoParaCotacao()
    {
        return PedidoRepository::updateStatus(
            $this->criarHistoricoRequest,
            $this->pedido_id,
            config('config.status.aguardando_cotacao')
        );
    }

    private function mandaItensParaCotacao()
    {
        $pedidoItens = PedidoRepository::getItensDoPedido($this->pedido_id);

        foreach ($pedidoItens as $key => $p) {
            PedidoRepository::atualizaStatusPedidoItem($p->id, config('config.status.aguardando_cotacao'), $this->criarHistoricoRequest);
        }
    }
}
