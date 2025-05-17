<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Estoque\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class AtualizaNCMRequest extends ServiceUseCase
{
    private int $ncm_id;
    private int $estoque_id;

    // Construtor
    public function __construct($ncm_id, $estoque_id, CriarHistoricoRequest $historicoRequest)
    {
        $this->ncm_id = $ncm_id;
        $this->estoque_id = $estoque_id;
        parent::__construct($historicoRequest);
    }

    public function getNcmId()
    {
        return $this->ncm_id;
    }

    public function getEstoqueId()
    {
        return $this->estoque_id;
    }
}
