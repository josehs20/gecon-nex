<?php

namespace Modules\Mercado\UseCases\Pdv\Venda;

use Exception;
use Modules\Mercado\Application\CaixaApplication;
use Modules\Mercado\Application\VendaApplication;
use Modules\Mercado\Entities\Venda;
use Modules\Mercado\Repository\Venda\VendaRepository;
use Modules\Mercado\Repository\VendaItem\VendaItemRepository;
use Modules\Mercado\UseCases\Pdv\Venda\Requests\AtualizaVendaItemRequest;
use Modules\Mercado\UseCases\Pdv\Venda\Requests\AtualizaVendaRequest;
use Modules\Mercado\UseCases\Pdv\Venda\Requests\CriarVendaItemRequest;
use Modules\Mercado\UseCases\Pdv\Venda\Rules\SomaValoresItens;

class AtualizaVenda
{
    private AtualizaVendaRequest $request;
    public function __construct(AtualizaVendaRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $this->validade();
        $valores = $this->somaValores();
        $venda = $this->atualizarVenda($valores);
        $itens = $this->criarVendaItens($venda);

        $this->removeItensDiferentes($itens, $venda->id);
        
        return $venda;
    }

    public function validade()
    {
        if (count($this->request->getCriarVendaRequest()->getItens()) == 0) {
            throw new Exception("Nenhum item adicionado", 1);
        }
    }

    public function somaValores()
    {
        return SomaValoresItens::handle($this->request->getCriarVendaRequest());
    }

    private function atualizarVenda($valores)
    {
        return VendaRepository::update(
            $this->request->getId(),
            $this->request->getCriarVendaRequest()->getCaixaId(),
            $this->request->getCriarVendaRequest()->getCaixaEvidenciaId(),
            $this->request->getCriarVendaRequest()->getLojaId(),
            $this->request->getCriarVendaRequest()->getUsuarioId(),
            $this->request->getCriarVendaRequest()->getStatusId(),
            $valores->subTotal,
            $valores->total,
            $valores->desconto_porcentagem,
            $valores->desconto_dinheiro,
            $this->request->getCriarVendaRequest()->getClienteId(),
            $this->request->getCriarVendaRequest()->getDataConclusao(),
            $this->request->getCriarVendaRequest()->getCriarHistoricoRequest()
        );
    }


    private function criarVendaItens(Venda $venda)
    {
        $itens = [];
        $vendaItensAtual = $venda->venda_itens->keyBy('estoque_id');

        foreach ($this->request->getCriarVendaRequest()->getItens() as $estoqueId => $item) {
            //verifica se existe o item na venda validando via estoque_id
            $itemNaVenda = $vendaItensAtual->get($estoqueId);
          
            if ($itemNaVenda) {
              
                $itens[] = VendaApplication::atualizaVendaItem(new AtualizaVendaItemRequest(
                    $itemNaVenda->id,
                    new CriarVendaItemRequest(
                        $this->request->getCriarVendaRequest()->getCriarHistoricoRequest(),
                        $venda->id,
                        $this->request->getCriarVendaRequest()->getCaixaId(),
                        $this->request->getCriarVendaRequest()->getCaixaEvidenciaId(),
                        $venda->loja_id,
                        $estoqueId,
                        $itemNaVenda->produto_id,
                        $item['qtd'],
                        $item['preco'],
                        $item['total']
                    )
                ));
            } else {
                $itens[] = VendaApplication::criaVendaItem(new CriarVendaItemRequest(
                    $this->request->getCriarVendaRequest()->getCriarHistoricoRequest(),
                    $venda->id,
                    $this->request->getCriarVendaRequest()->getCaixaId(),
                    $this->request->getCriarVendaRequest()->getCaixaEvidenciaId(),
                    $venda->loja_id,
                    $estoqueId,
                    $item['produtoId'],
                    $item['qtd'],
                    $item['preco'],
                    $item['total']
                ));
            }
        }
      
        return collect($itens);
    }

    private function removeItensDiferentes($itens, $venda_id)
    {
        return VendaItemRepository::removeItemDiferente($itens->pluck('id')->toArray(), $venda_id, $this->request->getCriarVendaRequest()->getCriarHistoricoRequest());
    }
}
