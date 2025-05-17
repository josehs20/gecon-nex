<?php

namespace App\UseCases\Loja;

use App\Repository\Loja\LojaRepository;
use App\UseCases\Loja\Requests\CriarInscricaoEstadualRequest;

class CriarInscricaoEstadual
{
    private CriarInscricaoEstadualRequest $request;

    public function __construct(CriarInscricaoEstadualRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        return $this->criarInscricaoEstadual();
    }

    private function validade() {

    }

    private function criarInscricaoEstadual() {
        return LojaRepository::createInscricaoEstadual(
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
