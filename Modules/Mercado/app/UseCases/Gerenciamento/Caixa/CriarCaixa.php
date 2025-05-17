<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Caixa;

use Modules\Mercado\Repository\Caixa\CaixaRepository;
use Modules\Mercado\UseCases\Gerenciamento\Caixa\Requests\CriarCaixaRequest;

class CriarCaixa
{
    private CriarCaixaRequest $request;
    public function __construct(CriarCaixaRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $this->validade();
        $caixas = self::criarCaixas();
        return $caixas;
    }

    public function validade()
    {
        //validacoes

    }

    public function criarCaixas()
    {
        $caixas = [];
        foreach ($this->request->getLojas() as $key => $loja_id) {
            $caixas[] = CaixaRepository::create(
                $this->request->getNome(),
                $loja_id,
                $this->request->getStatus(),
                true,
                $this->request->getCriarHistoricoRequest()
            );
        }
        return $caixas;
    }
}
