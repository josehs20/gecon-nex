<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Recebimento\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class ReceberRequest extends ServiceUseCase
{
    private int $compra_id;
    private int $status_id;
    private int $loja_id;
    private string $data_recebimento;
    private ?string $observacao;

    public function __construct(int $compra_id, int $status_id, int $loja_id, CriarHistoricoRequest $criarHistoricoRequest, string $data_recebimento, ?string $observacao = null)
    {
        parent::__construct($criarHistoricoRequest);
        $this->data_recebimento = $data_recebimento;
        $this->observacao = $observacao;
        $this->compra_id = $compra_id;
        $this->status_id = $status_id;
        $this->loja_id = $loja_id;
    }
    
    public function getCompraId(): int{
        return $this->compra_id;
    }

    public function getStatusId(): int{
        return $this->status_id;
    }

    public function getLojaId(): int{
        return $this->loja_id;
    }

    public function getDataRecebimento(): mixed
    {
        return $this->data_recebimento;
    }

    public function getObservacao(): string{
        return $this->observacao;
    }

}
