<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Exception;
use Modules\Mercado\Repository\PDV\CaixaPDVRepository;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class ExcluirOrcamento
{
    private int $id;
    private CriarHistoricoRequest $criar_historico_request;
    public function __construct(int $id, CriarHistoricoRequest $criar_historico_request)
    {
        $this->id = $id;
        $this->criar_historico_request = $criar_historico_request;
    }

    public function handle()
    {
        $this->validade();
        $this->delete_orcamento();
        return true;
    }
    private function validade()
    {
        $orcamento = CaixaPDVRepository::getOrcamentoById($this->id);
        if (!$orcamento) {
            throw new Exception("Orçamento não existe", 1);
        }
        return true;
    }

    private function delete_orcamento()
    {
        return CaixaPDVRepository::delete_orcamento($this->id, $this->criar_historico_request);
    }
}
