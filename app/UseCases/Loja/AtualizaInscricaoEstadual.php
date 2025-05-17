<?php

namespace App\UseCases\Loja;

use App\Repository\Loja\LojaRepository;
use App\UseCases\Loja\Requests\CriarInscricaoEstadualRequest;

class AtualizaInscricaoEstadual
{
    private CriarInscricaoEstadualRequest $request;
    private int $id;

    public function __construct($id, CriarInscricaoEstadualRequest $request)
    {
        $this->request = $request;
        $this->id = $id;
    }

    public function handle()
    {
        return $this->atualizaInscricaoEstadual();
    }

    private function validade() {

    }

    private function atualizaInscricaoEstadual() {
        return LojaRepository::atualizaInscricaoEstadual(
            $this->id,
            $this->request->getLojaId(),
            $this->request->getNfeioLojaId(),
            $this->request->getStateTaxId(),
            $this->request->getAccountId(),
            $this->request->getCompanyId(),
            $this->request->getCode(),
            $this->request->getSpecialTaxRegime(),
            $this->request->getType(),
            $this->request->getTaxNumber(),
            $this->request->getStatus(),
            $this->request->getSerie(),
            $this->request->getNumber(),
            $this->request->getProcessingDetails(),
            $this->request->getSecurityCredential()
        );
    }
}
