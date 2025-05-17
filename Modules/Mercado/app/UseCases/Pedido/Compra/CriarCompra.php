<?php

namespace Modules\Mercado\UseCases\Pedido\Compra;

use Exception;
use Modules\Mercado\Application\PedidoApplication;
use Modules\Mercado\Repository\Compra\CompraRepository;
use Modules\Mercado\Repository\Cotacao\CotacaoRepository;
use Modules\Mercado\Repository\Cotacao\CotForItemRepository;
use Modules\Mercado\Repository\Cotacao\CotFornecedorRepository;
use Modules\Mercado\Repository\Pedido\PedidoRepository;
use Modules\Mercado\UseCases\Pedido\Compra\Requests\CriarCompraRequest;

class CriarCompra
{
    private CriarCompraRequest $request;
    public function __construct(CriarCompraRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $cotacao_fornecedor = $this->validade();
        $compra = $this->criaCompra();
        $this->criaCompraItens($compra, $cotacao_fornecedor);
        $this->atualizaCotacao($cotacao_fornecedor);
        $this->atualizaCotForItens($cotacao_fornecedor);
        $this->atualizaPedidos($cotacao_fornecedor);
        $this->atualizaPedidoItens($cotacao_fornecedor);
        return $compra;
    }

    // Validação dos dados
    private function validade()
    {
        $cotacao_fornecedor = CotFornecedorRepository::getCotFornecedorById($this->request->getCotFornecedorId());
        //se a contação estiver sido alterada não realiza a compra
        if ($cotacao_fornecedor->cotacao->status_id != config('config.status.cotado')) {
            throw new Exception("É nenecessário que a cotação esteja com status Cotada. Status atual: " . $cotacao_fornecedor->cotacao->status->descricao(), 1);
        }

        return $cotacao_fornecedor;
    }

    private function criaCompra()
    {
        return CompraRepository::create(
            $this->request->getLojaId(),
            $this->request->getUsuarioId(),
            $this->request->getCotacaoId(),
            $this->request->getCotFornecedorId(),
            $this->request->getStatusId(),
            $this->request->getEspeciePagamentoId(),
            $this->request->getCriarHistoricoRequest()
        );
    }

    private function criaCompraItens($compra, $cotacao_fornecedor)
    {
        foreach ($cotacao_fornecedor->cot_for_itens as $key => $cfi) {
            CompraRepository::createCompraItem(
                $compra->id,
                $cfi->loja_id,
                $cfi->id,
                $this->request->getCriarHistoricoRequest()
            );
        }
    }

    private function atualizaCotacao($cotacao_fornecedor)
    {
        return CotacaoRepository::updateStatus($this->request->getCriarHistoricoRequest(), $cotacao_fornecedor->cotacao_id, $this->request->getStatusId());
    }

    private function atualizaCotForItens($cotacao_fornecedor)
    {
        foreach ($cotacao_fornecedor->cot_for_itens as $key => $cfi) {
            CotForItemRepository::atualizaStatus($this->request->getCriarHistoricoRequest(), $cfi->id, $this->request->getStatusId());
        }
    }

    private function atualizaPedidos($cotacao_fornecedor) {
        $pedidos_ids = $cotacao_fornecedor->cot_for_itens->pluck('pedido_id')->unique()->toArray();
        foreach ($pedidos_ids as $key => $id) {
            PedidoApplication::atualizaStatusPedido($this->request->getCriarHistoricoRequest(), $id,$this->request->getStatusId());
        }
    }

    private function atualizaPedidoItens($cotacao_fornecedor) {
        $pedidos = PedidoRepository::getPedidosById($cotacao_fornecedor->cot_for_itens->pluck('pedido_id')->unique()->toArray());
        foreach ($pedidos as $key => $p) {
            foreach ($p->pedido_itens as $key => $pi) {
                PedidoApplication::atualizaStatusPedidoItem($this->request->getCriarHistoricoRequest(), $pi->id, $this->request->getStatusId());
            }
        }
    }
}
