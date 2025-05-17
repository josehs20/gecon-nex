<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Modules\Mercado\Application\CaixaApplication;
use Modules\Mercado\Application\MovimentacaoEstoqueApplication;
use Modules\Mercado\Application\VendaApplication;
use Modules\Mercado\Entities\Venda;
use Modules\Mercado\Repository\Venda\VendaRepository;
use Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque\Requests\MovimentacaoEstoqueItemRequest;
use Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque\Requests\MovimentacaoEstoqueRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\CriarVendaPagamentoRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\EditarStatusCaixaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\FinalizarVendaRequest;
use Modules\Mercado\UseCases\Pdv\Venda\Requests\AtualizaVendaRequest;

class FinalizarVenda
{
    private FinalizarVendaRequest $request;

    public function __construct(FinalizarVendaRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $venda = $this->criaVenda();
        $this->criarVendaPagamentos($venda);
        $this->movimentaEstoques($venda);
        $this->updateCaixa();
       
        return $venda;
    }

    private function criaVenda()
    {
        if ($this->request->getVendaRequest()->getVendaId() != null) {
           return VendaApplication::atualizaVenda(new AtualizaVendaRequest($this->request->getVendaRequest()->getVendaId(), $this->request->getVendaRequest()));
        } else {
            return VendaApplication::criarVenda($this->request->getVendaRequest());
        }
    }

    private function criarVendaPagamentos(Venda $venda) {
        return CaixaApplication::criarVendaPagamento(new CriarVendaPagamentoRequest($this->request->getVendaRequest(), $venda));
    }
    
    private function movimentaEstoques(Venda $venda)
    {
        $vendaItens = $venda->venda_itens;

        $movimentacao = MovimentacaoEstoqueApplication::criarMovimentacaoEstoque(new MovimentacaoEstoqueRequest(
            $venda->loja_id,
            $venda->status_id,
            $this->request->getVendaRequest()->getUsuarioId(),
            config('config.tipo_movimentacao_estoque.venda'),
            $this->request->getVendaRequest()->getCriarHistoricoRequest()
        ));
        
        foreach ($vendaItens as $key => $item) {
            $estoqueId = $item->estoque_id;
            $qtd = $item->quantidade;

            MovimentacaoEstoqueApplication::movimentar(new MovimentacaoEstoqueItemRequest(
                $estoqueId,
                $movimentacao->id,
                config('config.tipo_movimentacao_estoque.venda'),
                $qtd,
                $this->request->getVendaRequest()->getCriarHistoricoRequest()
            ));
        }

        return MovimentacaoEstoqueApplication::finalizarMovimentacao($movimentacao->id, $this->request->getVendaRequest()->getCriarHistoricoRequest());
    }

    private function updateCaixa()
    {
        $statusId = config('config.status.livre');
        return CaixaApplication::editar_status(new EditarStatusCaixaRequest(
            $this->request->getVendaRequest()->getCriarHistoricoRequest(),
            $this->request->getVendaRequest()->getCaixaId(),
            $statusId,
            $this->request->getVendaRequest()->getUsuarioId()
        ));
    }
}
