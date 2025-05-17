<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Balanco;

use Modules\Mercado\Entities\Balanco;
use Modules\Mercado\Repository\Balanco\BalancoRepository;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class CancelarBalanco
{
    private int $balanco_id;
    private CriarHistoricoRequest $criarHistoricoRequest;
    public function __construct(int $balanco_id, CriarHistoricoRequest $criarHistoricoRequest)
    {
        $this->balanco_id = $balanco_id;
        $this->criarHistoricoRequest = $criarHistoricoRequest;
    }

    public function handle()
    {
        return $this->deleteBalanco();
        // return $this->deleteBalancoItens($this->deleteBalanco());
    }

    private function deleteBalanco()
    {
        return BalancoRepository::deleteBalanco($this->balanco_id, $this->criarHistoricoRequest);
    }

    private function deleteBalancoItens(Balanco $balanco)
    {
        $balancoItens = $balanco->balanco_itens;
        foreach ($balancoItens as $key => $value) {
            BalancoRepository::deleteBalancoItem($value->id, $this->criarHistoricoRequest);
        }

        return $balanco;
    }
}
