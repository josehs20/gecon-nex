<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Balanco;

use Modules\Mercado\Repository\Balanco\BalancoRepository;
use Modules\Mercado\UseCases\Gerenciamento\Balanco\Requests\BalancoRequest;

class UpdateBalanco
{
    private int $id;
    private BalancoRequest $request;
    public function __construct(int $id, BalancoRequest $request)
    {
        $this->id = $id;
        $this->request = $request;
    }

    public function handle()
    {
        return $this->updateBalanco();
    }

    private function updateBalanco(){
        return BalancoRepository::updateBalanco(
            $this->id,
            $this->request->getLojaId(),
            $this->request->getStatusId(),
            $this->request->getUsuarioId(),
            $this->request->getObservacao(),
            $this->request->getCriarHistoricoRequest()
        );
    }

}
