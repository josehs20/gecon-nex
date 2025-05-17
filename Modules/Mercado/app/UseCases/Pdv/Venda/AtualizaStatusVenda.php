<?php

namespace Modules\Mercado\UseCases\Pdv\Venda;

use Modules\Mercado\Repository\Venda\VendaRepository;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class AtualizaStatusVenda
{
    private int $vendaId;
    private int $statusId;
    private CriarHistoricoRequest $historicoReqquest;
    public function __construct(
        $vendaId,
        $statusId,
        CriarHistoricoRequest $historicoReqquest
    ) {
        $this->vendaId = $vendaId;
        $this->statusId = $statusId;
        $this->historicoReqquest = $historicoReqquest;
    }

    public function handle()
    {
        $venda = $this->atualizarStatusVenda();


        return $venda;
    }

    public function atualizarStatusVenda()
    {
        return VendaRepository::atualizaStatusVenda(
            $this->vendaId,
            $this->statusId,
            $this->historicoReqquest
        );
    }
}
