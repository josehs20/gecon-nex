<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Balanco;

use Modules\Mercado\Repository\Balanco\BalancoRepository;
use Modules\Mercado\UseCases\Gerenciamento\Balanco\Requests\BalancoRequest;

class CriarBalanco
{
    private BalancoRequest $request;
    public function __construct(BalancoRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        return $this->createBalanco();
    }

    private function createBalanco(){
        return BalancoRepository::createBalanco(
            $this->request->getLojaId(),
            $this->request->getStatusId(),
            $this->request->getUsuarioId(),
            $this->request->getObservacao(),
            $this->request->getCriarHistoricoRequest()
        );
    }

}
