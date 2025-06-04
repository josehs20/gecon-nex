<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Modules\Mercado\Repository\PDV\CaixaPDVRepository;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\SuprirCaixaRequest;

class SuprirCaixa
{
    private SuprirCaixaRequest $request;
    public function __construct(SuprirCaixaRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $this->validade();
        return $this->suprir();
    }

    private function validade()
    {

    }

    private function suprir()
    {
        return CaixaPDVRepository::criarSuprimentosAttrs($this->request->getCriarHistoricoRequest(), [
            'caixa_id' => $this->request->getCaixaId(),
            'caixa_evidencia_id' => $this->request->getCaixaEvidenciaId(),
            'caixa_diario_id' => $this->request->getCaixaDiarioId(),
            'usuario_id' => $this->request->getUsuarioId(),
            'valor' => $this->request->getValor(),
            'motivo' => $this->request->getMotivo(),
            'especie_pagamento_id' => $this->request->getEspeciePagamentoId(),
        ]);
    }
}
