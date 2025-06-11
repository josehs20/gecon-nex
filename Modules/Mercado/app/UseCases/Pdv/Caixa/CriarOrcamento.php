<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Exception;
use Modules\Mercado\Application\CaixaApplication;
use Modules\Mercado\Application\PDVApplication;
use Modules\Mercado\Repository\Caixa\CaixaRepository;
use Modules\Mercado\Repository\PDV\CaixaPDVRepository;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\EditarStatusCaixaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\OrcamentoRequest;

class CriarOrcamento
{
    private OrcamentoRequest $request;
    public function __construct(OrcamentoRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $itens = $this->validade();
        $orcamento = $this->criaOrcamento();
        $itens = $this->criaOrcamentoItens($orcamento, $itens);
        $this->limpaTemp();
        $this->atualizarStatusCaixa(config('config.status.livre'));
        return $orcamento;
    }

    private function validade()
    {
        $itens = CaixaPDVRepository::getItensCaixaTemp($this->request->getCaixaId());

        if (!$itens->count()) {
            throw new Exception("Adicione os itens para o orçamento.", 1);
        }

        return $itens;
    }

    private function limpaTemp()
    {
        return CaixaPDVRepository::limpaCaixaItensTemp($this->request->getCaixaId());
    }

    private function criaOrcamento()
    {
        $caixa = CaixaRepository::getCaixaById($this->request->getCaixaId());
        $caixaDiario = $caixa->diario_atual;
        $valores = PDVApplication::calculaTotaisVendaTemp($this->request->getCaixaId(), $this->request->getDescontoPorcentagem());

        return CaixaPDVRepository::criarOrcamentoAttrs($this->request->getCriarHistoricoRequest(), [
            'cliente_id' => $this->request->getClienteId(),
            'loja_id' => $this->request->getLojaId(),
            'empresa_master_cod' => $this->request->getEmpresaMasterCod(),
            'usuario_id' => $this->request->getUsuarioId(),
            'status_id' => $this->request->getStatusId(),
            'forma_pagamento_id' => $this->request->getFormaPagamentoId()[0]['id'],
            'caixa_diario_id' => $caixaDiario->id,
            'sub_total' => $valores['sub_total'],
            'total' => $valores['total'],
            'desconto_porcentagem' => $valores['desconto_percentual'],
            'desconto_dinheiro' => $valores['desconto_centavos'],
            'descricao' => $this->request->getCriarHistoricoRequest()->getComentario()
        ]);
    }

    private function criaOrcamentoItens($orcamento, $itens)
    {
        $orcamentoItens = [];
        $caixa = CaixaRepository::getCaixaById($this->request->getCaixaId());
        $caixaDiario = $caixa->diario_atual;
        foreach ($itens as $key => $i) {
            $orcamentoItens[] = CaixaPDVRepository::criarOrcamentoItemAttrs($this->request->getCriarHistoricoRequest(), [
                'orcamento_id' => $orcamento->id,
                'estoque_id' => $i->estoque_id,
                'loja_id' => $this->request->getLojaId(),
                'produto_id' => $i->produto_id,
                'caixa_diario_id' => $caixaDiario->id,
                'quantidade' => $i->quantidade,
                'preco' => $i->preco,
                'total' => $i->total,
            ]);
        }

        return $orcamentoItens;
    }

    private function atualizarStatusCaixa($status_id)
    {
        return CaixaPDVRepository::editaAttrsCaixa($this->request->getCriarHistoricoRequest(), $this->request->getCaixaId(), [
            'status_id' => $status_id
        ]);
    }
}
