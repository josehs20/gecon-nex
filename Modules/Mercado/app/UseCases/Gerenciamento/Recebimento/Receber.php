<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Recebimento;

use Exception;
use Modules\Mercado\Application\ArquivoApplication;
use Modules\Mercado\Application\MovimentacaoEstoqueApplication;
use Modules\Mercado\Application\PedidoApplication;
use Modules\Mercado\Application\RecebimentoApplication;
use Modules\Mercado\Repository\Pedido\PedidoRepository;
use Modules\Mercado\Repository\Recebimento\RecebimentoRepository;
use Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque\Requests\MovimentacaoEstoqueItemRequest;
use Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque\Requests\MovimentacaoEstoqueRequest;
use Modules\Mercado\UseCases\Gerenciamento\Recebimento\Requests\ReceberRequest;

class Receber
{
    private ReceberRequest $request;
    public function __construct(ReceberRequest $request)
    {
        $this->request = $request;
    }
    public function handle()
    {
        $pedido = $this->validade();
        $arquivo_id = $this->salvarArquivo($pedido);

        $recebimento = $this->criarRecebimento($pedido, $arquivo_id);
        $itemRecebidos = $this->criarRecebimentoItens($pedido, $recebimento);
        $this->movimentaEstoques($recebimento, $itemRecebidos);
        $this->verificaPedidoStatusPedido($recebimento);
        return $recebimento;
    }
    private function validade()
    {
        $pedido = PedidoRepository::getPedidoById($this->request->getPedidoId());
        if (!$pedido) {
            throw new Exception("Pedido não existe.", 1);
        }
        return $pedido;
    }

    private function salvarArquivo($pedido)
    {
        $arquivo_id = null;
        if ($this->request->getArquivo()) {
            $arquivo = ArquivoApplication::createArquivo($this->request->getArquivo(), config('config.tipo_arquivo.nota_fiscal'), $pedido->loja_id, $this->request->getCriarHistoricoRequest());
            $arquivo_id = $arquivo->id;
        }
        return $arquivo_id;
    }

    private function criarRecebimento($pedido, $arquivo_id = null)
    {
        if ($pedido->recebimento) {

            return RecebimentoRepository::update(
                $this->request->getCriarHistoricoRequest(),
                $pedido->recebimento->id,
                $pedido->id,
                $this->request->getCriarHistoricoRequest()->getUsuarioId(),
                $pedido->loja_id,
                $pedido->recebimento->status_id,
                $this->request->getDataRecebimento(),
                $arquivo_id,
                $this->request->getCriarHistoricoRequest()->getComentario()

            );
        } else {
            return RecebimentoRepository::create(
                $this->request->getCriarHistoricoRequest(),
                $pedido->id,
                $this->request->getCriarHistoricoRequest()->getUsuarioId(),
                $pedido->loja_id,
                config('config.status.concluido'),
                $this->request->getDataRecebimento(),
                $arquivo_id,
                $this->request->getCriarHistoricoRequest()->getComentario()

            );
        }
    }

    private function criarRecebimentoItens($pedido, $recebimento)
    {
        $recebimentoItens = [];
        $itensRecebidos = collect($this->request->getItens());
        foreach ($pedido->pedido_itens as $key => $item) {
            $status = config('config.status.concluido');
            
            $itemRecebido = $itensRecebidos->first(function ($vl) use ($item) {
                return $vl['pedido_item_id'] == $item->id;
            });
           
            if ($itemRecebido) {
                $itemRecebido = (object) $itemRecebido;
                $validade = $itemRecebido->validade ?? null;
                $lote = $itemRecebido->lote ?? null;
              
                $qtdRecebidaItem = converteDinheiroParaFloat($itemRecebido->quantidade_recebida);

                $recebimentoItem = RecebimentoRepository::getRecebimentoItemByPedidoItemID($item->id);
               
                if (!$recebimentoItem) {
                    $qtdRecebida = $qtdRecebidaItem; //fazer a logica depois de quantidade recebida
                    $status = round(floatval($qtdRecebida), 3) >= round(floatval($item->quantidade_pedida), 3) ? $status : config('config.status.aberto');
                    $recebimentoItem = RecebimentoRepository::createRecebimentoItem(
                        $this->request->getCriarHistoricoRequest(),
                        $recebimento->id,
                        $recebimento->loja_id,
                        $item->produto_id,
                        $item->estoque_id,
                        $item->id,
                        $status,
                        $qtdRecebida,
                        $item->quantidade_pedida,
                        $item->preco_unitario,
                        $item->total,
                        $lote,
                        $validade
                    );
                } else {
                    $qtdRecebida = $qtdRecebidaItem + $item->recebimento_item->quantidade_recebida; //fazer a logica depois de quantidade recebida
                    $status = round(floatval($qtdRecebida), 3) >= round(floatval($item->quantidade_pedida), 3) ? $status : config('config.status.aberto');
                   
                    $recebimentoItem = RecebimentoRepository::updateRecebimentoItem(
                        $this->request->getCriarHistoricoRequest(),
                        $recebimentoItem->id,
                        $recebimento->id,
                        $recebimento->loja_id,
                        $item->produto_id,
                        $item->estoque_id,
                        $item->id,
                        $status,
                        $qtdRecebida,
                        $item->quantidade_pedida,
                        $item->preco_unitario,
                        $item->total,
                        $recebimentoItem->lote,
                        $recebimentoItem->validade
                    );
                }
                $recebimentoItem->recebida_agora = $qtdRecebidaItem;
                $recebimentoItens[] = $recebimentoItem;
            }
        }
      
        return $recebimentoItens;
    }

    private function verificaPedidoStatusPedido($recebimento)
    {
        $existeItemPendente = $recebimento->recebimento_itens->first(function ($item) {
            return $item->status_id == config('config.status.aberto');
        });

        if ($existeItemPendente) {
            $recebimento = RecebimentoApplication::atualizaStatus($recebimento->id, config('config.status.aberto'), $this->request->getCriarHistoricoRequest());
        }else {
            $recebimento = RecebimentoApplication::atualizaStatus($recebimento->id, config('config.status.concluido'), $this->request->getCriarHistoricoRequest());

        }

        if ($recebimento->status_id == config('config.status.concluido')) {
            return PedidoApplication::atualizaStatusPedido($this->request->getCriarHistoricoRequest(), $recebimento->pedido_id, $recebimento->status_id);
        }
    }

    private function movimentaEstoques($recebimento, $itensRecebidos)
    {

        $movimentacao = MovimentacaoEstoqueApplication::criarMovimentacaoEstoque(new MovimentacaoEstoqueRequest(
            $recebimento->loja_id,
            config('config.status.concluido'),
            $this->request->getCriarHistoricoRequest()->getUsuarioId(),
            config('config.tipo_movimentacao_estoque.recebimento'),
            $this->request->getCriarHistoricoRequest()
        ));

        foreach ($itensRecebidos as $key => $item) {
            MovimentacaoEstoqueApplication::movimentar(new MovimentacaoEstoqueItemRequest(
                $item->estoque_id,
                $movimentacao->id,
                config('config.tipo_movimentacao_estoque.recebimento'),
                $item->recebida_agora,
                $this->request->getCriarHistoricoRequest()
            ));
        }
        
        return MovimentacaoEstoqueApplication::finalizarMovimentacao($movimentacao->id, $this->request->getCriarHistoricoRequest());
    }
}
