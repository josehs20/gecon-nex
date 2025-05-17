<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Recebimento\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class ReceberNFRequest extends ServiceUseCase
{
    private mixed $data_recebimento;
    private $chave_nova;
    private $loja_id;

    // Construtor para inicializar os parâmetros
    public function __construct(CriarHistoricoRequest $criarHistoricoRequest, $chave_nova, mixed $data_recebimento, int $loja_id)
    {
        parent::__construct($criarHistoricoRequest);
        $this->chave_nova = $chave_nova;
        $this->data_recebimento = $data_recebimento;
        $this->loja_id = $loja_id;
    }

    public function getChaveNota()
    {
        return $this->chave_nova;
    }

    public function getLojaId()
    {
        return $this->loja_id;
    }

    // Métodos Getter e Setter para 'data_recebimento'
    public function getDataRecebimento(): mixed
    {
        return $this->data_recebimento;
    }

    public function setDataRecebimento(mixed $data_recebimento): void
    {
        $this->data_recebimento = $data_recebimento;
    }
}
