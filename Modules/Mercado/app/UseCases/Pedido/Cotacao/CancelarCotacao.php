<?php

namespace Modules\Mercado\UseCases\Pedido\Cotacao;

use Modules\Mercado\Application\PedidoApplication;
use Modules\Mercado\Entities\Cotacao;
use Modules\Mercado\Repository\Cotacao\CotacaoRepository;
use Modules\Mercado\Repository\Cotacao\CotForItemRepository;
use Modules\Mercado\Repository\Pedido\PedidoRepository;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class CancelarCotacao
{
    private CriarHistoricoRequest $criarHistoricoRequest;
    private int $cotacao_id;

    public function __construct(int $cotacao_id, CriarHistoricoRequest $criarHistoricoRequest)
    {
        $this->criarHistoricoRequest = $criarHistoricoRequest;
        $this->cotacao_id = $cotacao_id;
    }

    public function handle()
    {
        $this->validade();
        $cotacao = $this->cancelaCotacao();
        $this->cancelaCotForItens($cotacao);
        $this->voltaPedidoParaEstagioAnterior($cotacao);
    }

    // Validação dos dados
    private function validade() {}

    // Criação do pedido
    private function cancelaCotacao()
    {
        return CotacaoRepository::updateStatus(
            $this->criarHistoricoRequest,
            $this->cotacao_id,
            config('config.status.cancelado')
        );
    }

    private function cancelaCotForItens($cotacao)
    {
        foreach ($cotacao->cot_for_itens as $key => $cfi) {
            CotForItemRepository::atualizaStatus(
                $this->criarHistoricoRequest,
                $cfi->id,
                config('config.status.cancelado')
            );
        }
        return $cotacao;
    }

    private function voltaPedidoParaEstagioAnterior($cotacao)
    {
        $pedidos = PedidoRepository::getPedidosById($cotacao->cot_for_itens->pluck('pedido_id')->unique()->toArray());
        $status_id = config('config.status.aguardando_cotacao');
        foreach ($pedidos as $key => $p) {
            PedidoApplication::atualizaStatusPedido($this->criarHistoricoRequest, $p->id, $status_id);
            $p->pedido_itens->each(function ($item) use ($status_id) {
                PedidoApplication::atualizaStatusPedidoItem($this->criarHistoricoRequest, $item->id, $status_id);
            });
        }
        return $cotacao;
    }
}
