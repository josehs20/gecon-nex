<?php

namespace Modules\Mercado\UseCases\Pedido\Compra;

use Exception;
use Modules\Mercado\Application\PedidoApplication;
use Modules\Mercado\Repository\Compra\CompraRepository;
use Modules\Mercado\Repository\Cotacao\CotacaoRepository;
use Modules\Mercado\Repository\Cotacao\CotForItemRepository;
use Modules\Mercado\Repository\Pedido\PedidoRepository;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class CancelarCompra
{
    private CriarHistoricoRequest $criarHistoricoRequest;
    private int $id;
    public function __construct(CriarHistoricoRequest $criarHistoricoRequest, int $id)
    {
        $this->criarHistoricoRequest = $criarHistoricoRequest;
        $this->id = $id;
    }

    public function handle()
    {
        $compra = $this->validade();
        $this->atualizaStatusCompra();
        $this->atualizaCotacao($compra);
        $this->atualizaCotForItens($compra);
        $this->atualizaPedidos($compra);
        $this->atualizaPedidoItens($compra);

        return $compra;
    }

    // Validação dos dados
    private function validade()
    {
        $compra = CompraRepository::getCompraById($this->id);
        //se a contação estiver sido alterada não realiza a compra
        if ($compra->status_id != config('config.status.comprado')) {
            throw new Exception("É nenecessário que a compra esteja com status comprada. Status atual: " . $compra->status->descricao(), 1);
        }

        return $compra;
    }

    private function atualizaStatusCompra()
    {
        $status = config('config.status.cancelado');
        return CompraRepository::atualizaStatus(
            $this->id,
            $status,
            $this->criarHistoricoRequest
        );
    }

    private function atualizaCotacao($compra)
    {
        $status = config('config.status.cotado');
        return CotacaoRepository::updateStatus($this->criarHistoricoRequest, $compra->cotacao_id, $status);
    }

    private function atualizaCotForItens($compra)
    {
        $status = config('config.status.cotado');
        foreach ($compra->cot_fornecedor->cot_for_itens as $key => $cfi) {
            CotForItemRepository::atualizaStatus($this->criarHistoricoRequest, $cfi->id, $status);
        }
    }

    private function atualizaPedidos($compra)
    {
        $status = config('config.status.cotado');
        $pedidos_ids = $compra->cot_fornecedor->cot_for_itens->pluck('pedido_id')->unique()->toArray();
        foreach ($pedidos_ids as $key => $id) {
            PedidoApplication::atualizaStatusPedido($this->criarHistoricoRequest, $id, $status);
        }
    }

    private function atualizaPedidoItens($compra)
    {
        $pedidos = PedidoRepository::getPedidosById($compra->cot_fornecedor->cot_for_itens->pluck('pedido_id')->unique()->toArray());
        $status = config('config.status.cotado');

        foreach ($pedidos as $key => $p) {
            foreach ($p->pedido_itens as $key => $pi) {
                PedidoApplication::atualizaStatusPedidoItem($this->criarHistoricoRequest, $pi->id, $status);
            }
        }
    }
}
