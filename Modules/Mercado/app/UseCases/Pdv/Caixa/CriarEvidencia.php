<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Modules\Mercado\Repository\Caixa\CaixaRepository;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\CriarEvidenciaRequest;

class CriarEvidencia
{
    private CriarEvidenciaRequest $request;
    public function __construct(CriarEvidenciaRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        return $this->criarEvidencias();
    }

    private function criarEvidencias() {
        return CaixaRepository::criarEvidencia(
            $this->request->getCriarHistoricoRequest(),
            $this->request->getCaixaId(),
            $this->request->getAcaoId(),
            $this->request->getUsuarioId(),
            $this->request->getCaixaRecursoId(),
            $this->request->getValorTotal(),
            $this->request->getValorDinheiro(),
            $this->request->getDescricao()
        );
    }
}
