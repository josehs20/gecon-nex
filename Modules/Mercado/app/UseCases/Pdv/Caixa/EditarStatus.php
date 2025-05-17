<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Modules\Mercado\Repository\Caixa\CaixaRepository;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\EditarStatusCaixaRequest;

class EditarStatus
{
    private EditarStatusCaixaRequest $request;

    public function __construct(EditarStatusCaixaRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        return $this->editarStatus();
    }

    private function editarStatus()
    {
        $ativo = $this->request->getStatusId() == config('config.status.fechado') ? 0 : 1;
        return CaixaRepository::updateStatus(
            $this->request->getId(),
            $this->request->getStatusId(),
            $this->request->getUsuarioId(),
            $ativo,
            $this->request->getCriarHistoricoRequest()
        );
    }
}
