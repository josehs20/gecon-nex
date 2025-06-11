<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Modules\Mercado\Application\PDVApplication;
use Modules\Mercado\Repository\PDV\CaixaPDVRepository;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\AdicionarItemTempRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\CancelarVendaRequest;

class ColocarOrcamentoEmVenda
{
    private int $orcamento_id;
    private int $caixa_id;
    private CriarHistoricoRequest $criar_historico_request;
    public function __construct(int $orcamento_id, int $caixa_id , CriarHistoricoRequest $criar_historico_request)
    {
        $this->orcamento_id = $orcamento_id;
        $this->caixa_id = $caixa_id;
        $this->criar_historico_request = $criar_historico_request;
    }

    public function handle()
    {
        $this->limpaTempAtual();
        $orcamento = $this->getOrcamento();
        $temps = $this->adicionaItensTemp($orcamento);
        return CaixaPDVRepository::getItensCaixaTemp($this->caixa_id);
    }

    private function limpaTempAtual()
    {
        CaixaPDVRepository::limpaCaixaItensTemp($this->caixa_id);
    }

    private function adicionaItensTemp($orcamento)
    {
        foreach ($orcamento->orcamento_itens as $key => $oi) {
            PDVApplication::adicionar_item_temp(new AdicionarItemTempRequest(
                $oi->estoque_id,
                $oi->quantidade,
                $this->caixa_id,
                $this->criar_historico_request
            ));
        }
    }

    private function getOrcamento()
    {
        return CaixaPDVRepository::getOrcamentoById($this->orcamento_id);
    }
}
