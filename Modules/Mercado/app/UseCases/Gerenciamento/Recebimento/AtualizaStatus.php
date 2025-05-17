<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Recebimento;

use Modules\Mercado\Repository\Recebimento\RecebimentoRepository;
use Modules\Mercado\UseCases\Gerenciamento\Recebimento\Requests\ReceberRequest;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class AtualizaStatus
{
    private CriarHistoricoRequest $criarHistoricoRequest;
    private int $status_id;
    private int $id;
    public function __construct(int $id, int $status_id, CriarHistoricoRequest $criarHistoricoRequest)
    {
        $this->status_id = $status_id;
        $this->id = $id;
        $this->criarHistoricoRequest = $criarHistoricoRequest;
    }
    public function handle() {
        return $this->atualizaStatus();
    }

    private function atualizaStatus() {
        return RecebimentoRepository::atualizaStatusRecebimento(
            $this->criarHistoricoRequest,
            $this->id,
            $this->status_id
        );
    }
}
