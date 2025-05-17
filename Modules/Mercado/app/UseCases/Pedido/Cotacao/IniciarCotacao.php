<?php

namespace Modules\Mercado\UseCases\Pedido\Cotacao;

use Exception;
use Modules\Mercado\Application\CotacaoApplication;
use Modules\Mercado\Application\PedidoApplication;
use Modules\Mercado\Repository\Pedido\PedidoRepository;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\Pedido\Cotacao\Requests\CriarCotacaoRequest;
use Modules\Mercado\UseCases\Pedido\Cotacao\Requests\CriarCotForItensRequest;
use Modules\Mercado\UseCases\Pedido\Cotacao\Requests\CriarCotFornecedorRequest;

class IniciarCotacao
{
    private array $pedidoIds;
    private array $fornecedorIds;
    private CriarHistoricoRequest $criarHistoricoRequest;

    public function __construct(array $pedidoIds, array $fornecedorIds, CriarHistoricoRequest $criarHistoricoRequest)
    {
        $this->pedidoIds = $pedidoIds;
        $this->fornecedorIds = $fornecedorIds;
        $this->criarHistoricoRequest = $criarHistoricoRequest;
    }

    public function handle()
    {
        $this->validate();
        $cotacao = $this->criarCotacao();
        $cotFornecedores = $this->criarCotFornecedor($cotacao);
        $cotForItens = $this->criarCotForItens($cotFornecedores);
        $this->atualizaStatusPedido();
        return $cotacao;
    }

    private function validate()
    {
        $pedidos = PedidoRepository::getPedidosById($this->pedidoIds);
        foreach ($pedidos as $key => $p) {
            if ($p->status_id != config('config.status.aguardando_cotacao')) {
                throw new Exception("O pedido: ". $p->id . " foi removido da cotação.", 1);
            }
        }
    }

    private function criarCotacao()
    {
        $status = config('config.status.aberto');
        return CotacaoApplication::criarCotacao(new CriarCotacaoRequest(
            $this->criarHistoricoRequest,
            $this->criarHistoricoRequest->getLojaId(),
            $status,
            $this->criarHistoricoRequest->getUsuarioId()
        ));
    }

    private function criarCotFornecedor($cotacao)
    {
        $cotFornecedores = [];
        foreach ($this->fornecedorIds as $key => $id) {

            $cotFornecedores[] = CotacaoApplication::criarCotFornecedor(
                new CriarCotFornecedorRequest(
                    $this->criarHistoricoRequest,
                    $cotacao->id,
                    $cotacao->loja_id,
                    (int)$id
                )
            );
        }
        return $cotFornecedores;
    }

    private function criarCotForItens($cotFornecedores)
    {
        $pedidos = PedidoRepository::getPedidosById($this->pedidoIds);
        $cotForItens = [];
        //percorre cotFornecedores
        foreach ($cotFornecedores as $key => $cf) {
            //cria o cotForItens de cada cotFornecedor
            //então percorre cada pedido
            foreach ($pedidos as $key => $p) {
                $cotForItens[$cf->id][] = CotacaoApplication::criarCotForItens(new CriarCotForItensRequest(
                    $p->id,
                    $cf->id,
                    $this->criarHistoricoRequest
                ));
            }
        }

        return $cotForItens;
    }

    private function atualizaStatusPedido()
    {
        $pedidos = PedidoRepository::getPedidosById($this->pedidoIds);
        $status_id = config('config.status.em_cotacao');
        foreach ($pedidos as $key => $p) {
            PedidoApplication::atualizaStatusPedido($this->criarHistoricoRequest, $p->id, $status_id);
            foreach ($p->pedido_itens as $key => $pi) {
                PedidoApplication::atualizaStatusPedidoItem($this->criarHistoricoRequest, $pi->id, $status_id);
            }
        }
    }
}
