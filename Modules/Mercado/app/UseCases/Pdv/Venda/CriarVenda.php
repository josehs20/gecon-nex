<?php

namespace Modules\Mercado\UseCases\Pdv\Venda;

use Exception;
use Modules\Mercado\Application\CaixaApplication;
use Modules\Mercado\Application\VendaApplication;
use Modules\Mercado\Entities\Venda;
use Modules\Mercado\Repository\Caixa\CaixaRepository;
use Modules\Mercado\Repository\Venda\VendaRepository;
use Modules\Mercado\UseCases\Pdv\Venda\Requests\CriarVendaItemRequest;
use Modules\Mercado\UseCases\Pdv\Venda\Requests\CriarVendaRequest;
use Modules\Mercado\UseCases\Pdv\Venda\Rules\SomaValoresItens;

class CriarVenda
{
    private CriarVendaRequest $request;
    public function __construct(CriarVendaRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $this->validade();
        $valores = $this->somaValores();
      
        $venda = $this->criarVenda($valores);

        $this->criarVendaItens($venda);
        return $venda;
    }

    public function validade()
    {
        if (count($this->request->getItens()) == 0) {
            throw new Exception("Nenhum item adicionado", 1);
        }
    }

    public function somaValores()
    {
        return SomaValoresItens::handle($this->request);
    }

    private function criarVenda($valores)
    {     
        return VendaRepository::create(
            $this->request->getCaixaId(),
            $this->request->getCaixaEvidenciaId(),
            $this->request->getLojaId(),
            $this->request->getUsuarioId(),
            $this->request->getStatusId(),
            $valores->subTotal,
            $valores->total,
            CaixaApplication::gerarNumeroVenda($this->request->getLojaId()),
            $valores->desconto_porcentagem,
            $valores->desconto_dinheiro,
            $this->request->getClienteId(),
            $this->request->getDataConclusao(),
            $this->request->getCriarHistoricoRequest()
        );
    }


    private function criarVendaItens(Venda $venda)
    {
        $itensRequest = [];
        foreach ($this->request->getItens() as $estoqueId => $item) {
            $itensRequest[] = new CriarVendaItemRequest(
                $this->request->getCriarHistoricoRequest(),
                $venda->id,
                $venda->caixa_id,
                $venda->caixa_evidencia_id,
                $venda->loja_id,
                $estoqueId,
                $item['produtoId'],
                $item['qtd'],
                $item['preco'],
                $item['total']
            );
        }
      
        VendaApplication::criaVendaItens($itensRequest);
    }
}
