<?php

namespace Modules\Mercado\UseCases\Pedido\Pedido;

use Modules\Mercado\Repository\Pedido\PedidoRepository;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class AtualizaStatusPedidoItem
{
    private CriarHistoricoRequest $criarHistoricoRequest;
    private int $pedido_item_id;
    private int $status_id;

    public function __construct(CriarHistoricoRequest $criarHistoricoRequest, int $pedido_item_id, int $status_id)
    {
        $this->criarHistoricoRequest = $criarHistoricoRequest;
        $this->pedido_item_id = $pedido_item_id;
        $this->status_id = $status_id;
    }

    public function handle()
    {
        $this->validade();
        return $this->atualizaStatus();
    }

    // Validação dos dados
    private function validade()
    {

    }

    // Criação do pedido
    private function atualizaStatus()
    {
        return PedidoRepository::atualizaStatusPedidoItem(
            $this->pedido_item_id,
            $this->status_id,
            $this->criarHistoricoRequest
        );
    }

}
