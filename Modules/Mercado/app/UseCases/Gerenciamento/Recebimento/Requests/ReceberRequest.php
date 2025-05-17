<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Recebimento\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class ReceberRequest extends ServiceUseCase
{
    private int $pedido_id;
    private mixed $data_recebimento;
    private mixed $arquivo;
    private array $itens;

    // Construtor para inicializar os parâmetros
    public function __construct(CriarHistoricoRequest $criarHistoricoRequest, int $pedido_id, mixed $data_recebimento, $itens = [], $arquivo = null)
    {
        parent::__construct($criarHistoricoRequest);
        $this->pedido_id = $pedido_id;
        $this->data_recebimento = $data_recebimento;
        $this->arquivo = $arquivo;
        $this->itens = $itens;
    }

    public function getItens()
    {
        return $this->itens;
    }

    // Métodos Getter e Setter para 'pedido_id'
    public function getArquivo()
    {
        return $this->arquivo;
    }

    // Métodos Getter e Setter para 'pedido_id'
    public function getPedidoId(): int
    {
        return $this->pedido_id;
    }

    public function setPedidoId(int $pedido_id): void
    {
        $this->pedido_id = $pedido_id;
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
