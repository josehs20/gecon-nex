<?php

namespace Modules\Mercado\UseCases\Pedido\Pedido;

use Modules\Mercado\Repository\Pedido\PedidoRepository;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class AtualizaStatusPedido
{
    private CriarHistoricoRequest $criarHistoricoRequest;
    private int $pedido_id;
    private int $status_id;

    public function __construct(CriarHistoricoRequest $criarHistoricoRequest, int $pedido_id, int $status_id)
    {
        $this->criarHistoricoRequest = $criarHistoricoRequest;
        $this->pedido_id = $pedido_id;
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
        return PedidoRepository::updateStatus(
            $this->criarHistoricoRequest,
            $this->pedido_id,
            $this->status_id
        );
    }

}
