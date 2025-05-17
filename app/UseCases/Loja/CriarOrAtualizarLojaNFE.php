<?php

namespace App\UseCases\Loja;

use App\Models\Loja;
use App\Repository\Loja\LojaRepository;
use App\UseCases\Loja\Requests\CriarOrAtualizarLojaNFERequest;

class CriarOrAtualizarLojaNFE
{
    private CriarOrAtualizarLojaNFERequest $request;

    public function __construct(CriarOrAtualizarLojaNFERequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $this->validacoes();

        return $this->criarOrAtualiza();
    }

    private function validacoes() {}

    private function criarOrAtualiza()
    {
        $loja = Loja::find($this->request->getLojaId());
        if (!$loja->nfeio) {
            return LojaRepository::criarLojaNFE(
                $this->request->getEmpresaId(),
                $this->request->getLojaId(),
                $this->request->getNfeioId(),
                $this->request->getAccountId(),
                $this->request->getName(),
                $this->request->getTradeName(),
                $this->request->getFederalTaxNumber(),
                $this->request->getTaxRegime(),
                $this->request->getStatus(),
                $this->request->getAddress()
            );
        } else {
            return LojaRepository::atualizaLojaNFE(
                $this->request->getEmpresaId(),
                $this->request->getLojaId(),
                $this->request->getNfeioId(),
                $this->request->getAccountId(),
                $this->request->getName(),
                $this->request->getTradeName(),
                $this->request->getFederalTaxNumber(),
                $this->request->getTaxRegime(),
                $this->request->getStatus(),
                $this->request->getAddress()
            );
        }
    }
}
