<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Caixa;

use Exception;
use Modules\Mercado\Repository\Caixa\CaixaRepository;
use Modules\Mercado\UseCases\Gerenciamento\Caixa\Requests\AtualizarCaixaRequest;

class AtualizarCaixa
{
    private AtualizarCaixaRequest $request;

    public function __construct(AtualizarCaixaRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $this->validade();
        $caixa = self::atualizarCaixas();
        return $caixa;
    }

    public function validade()
    {
        $caixa = CaixaRepository::getCaixaById($this->request->getId());
        if ($caixa->status_id != config('config.status.fechado')) {
            throw new Exception("O caixa precisa estar fechado para altera-lo. Status atual: " . $caixa->status->descricao(), 1);
        }
    }

    public function atualizarCaixas()
    {
        $caixa = CaixaRepository::getCaixaById($this->request->getId());
        return CaixaRepository::update(
            $this->request->getId(),
            $this->request->getNome(),
            $caixa->status_id,
            $this->request->getAtivo(),
            $this->request->getCriarHistoricoRequest()
        );
    }
}
