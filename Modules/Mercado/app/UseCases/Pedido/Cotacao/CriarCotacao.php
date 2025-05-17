<?php

namespace Modules\Mercado\UseCases\Pedido\Cotacao;

use Modules\Mercado\Repository\Cotacao\CotacaoRepository;
use Modules\Mercado\UseCases\Pedido\Cotacao\Requests\CriarCotacaoRequest;

class CriarCotacao
{
    private CriarCotacaoRequest $request;

    public function __construct(CriarCotacaoRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        return $this->criar();
    }

    // Validação dos dados
    private function validade()
    {

    }

    private function criar()
    {
        return CotacaoRepository::create(
            $this->request->getCriarHistoricoRequest(),
            $this->request->getLojaId(),
            $this->request->getStatusId(),
            $this->request->getUsuarioId(),
            now(),
            null,
            null
        );
    }

}
