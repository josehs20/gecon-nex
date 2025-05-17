<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Balanco;

use Modules\Mercado\Repository\Balanco\BalancoRepository;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class DeletaBalancoItem
{
    private int $balanco_item_id;
    private CriarHistoricoRequest $criarHistoricoRequest;
    public function __construct(int $balanco_item_id, CriarHistoricoRequest $criarHistoricoRequest)
    {
        $this->balanco_item_id = $balanco_item_id;
        $this->criarHistoricoRequest = $criarHistoricoRequest;
    }

    public function handle()
    {
      return BalancoRepository::deleteBalancoItem($this->balanco_item_id, $this->criarHistoricoRequest);
    }
}