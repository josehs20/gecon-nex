<?php

namespace Modules\Mercado\UseCases\Pdv\Venda\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class CriarVendaRequest extends ServiceUseCase
{
    private array $itens;
    private int $caixa_id;
    private int $caixa_evidencia_id;
    private int $loja_id;
    private int $usuario_id;
    private int $status_id;
    private ?array $formaPagamento_id;
    private ?float $desconto_porcentagem;
    private ?int $cliente_id;
    private ?int $venda_id;
    private ?float $valor_recebido;
    private ?float $troco;
    private mixed $data_conclusao;

    public function __construct(
        CriarHistoricoRequest $historicoRequest,
        array $itens,
        int $caixa_id,
        int $caixa_evidencia_id,
        int $loja_id,
        int $usuario_id,
        int $status_id,
        ?int $cliente_id = null,
        ?array $formaPagamento_id = [],
        ?float $desconto_porcentagem = null,
        ?int $venda_id = null,
        ?float $valor_recebido = null,
        mixed $data_conclusao = null
    ) {
        parent::__construct($historicoRequest);
        $this->itens = $itens;
        $this->caixa_id = $caixa_id;
        $this->caixa_evidencia_id = $caixa_evidencia_id;
        $this->loja_id = $loja_id;
        $this->usuario_id = $usuario_id;
        $this->status_id = $status_id;
        $this->formaPagamento_id = $formaPagamento_id;
        $this->desconto_porcentagem = $desconto_porcentagem;
        $this->cliente_id = $cliente_id;
        $this->venda_id = $venda_id;
        $this->valor_recebido = $valor_recebido;
        $this->troco = null; //vai ser calculado durante o processmento
        $this->data_conclusao = $data_conclusao;
    }

    public function getItens(): array
    {
        return $this->itens;
    }

    public function setItens(array $itens): void
    {
        $this->itens = $itens;
    }

    public function getCaixaId(): int
    {
        return $this->caixa_id;
    }

    public function setCaixaId(int $caixa_id): void
    {
        $this->caixa_id = $caixa_id;
    }

    public function getCaixaEvidenciaId(): int
    {
        return $this->caixa_evidencia_id;
    }

    public function setCaixaEvidenciaId(int $caixa_evidencia_id): void
    {
        $this->caixa_evidencia_id = $caixa_evidencia_id;
    }

    public function getLojaId(): int
    {
        return $this->loja_id;
    }

    public function setLojaId(int $loja_id): void
    {
        $this->loja_id = $loja_id;
    }

    public function getUsuarioId(): int
    {
        return $this->usuario_id;
    }

    public function setUsuarioId(int $usuario_id): void
    {
        $this->usuario_id = $usuario_id;
    }

    public function getStatusId(): int
    {
        return $this->status_id;
    }

    public function setStatusId(int $status_id): void
    {
        $this->status_id = $status_id;
    }

    public function getFormaPagamentoId(): ?array
    {
        return $this->formaPagamento_id;
    }

    public function setFormaPagamentoId(?array $formaPagamento_id = null): void
    {
        $this->formaPagamento_id = $formaPagamento_id;
    }

    public function getDescontoPorcentagem(): ?float
    {
        return $this->desconto_porcentagem;
    }

    public function setDescontoPorcentagem(?float $desconto_porcentagem): void
    {
        $this->desconto_porcentagem = $desconto_porcentagem;
    }

    public function getClienteId(): ?int
    {
        return $this->cliente_id;
    }

    public function setClienteId(int $cliente_id): void
    {
        $this->cliente_id = $cliente_id;
    }

    public function getVendaId(): ?int
    {
        return $this->venda_id;
    }

    public function setVendaId(int $venda_id = null): void
    {
        $this->venda_id = $venda_id;
    }

    public function getValoRecebido(): ?float
    {
        return $this->valor_recebido;
    }

    public function setValoRecebido(float $valor_recebido = null): void
    {
        $this->valor_recebido = $valor_recebido;
    }

    public function getTroco(): ?float
    {
        return $this->troco;
    }

    public function setTroco(float $troco = null): void
    {
        $this->troco = $troco;
    }

    public function getDataConclusao(): mixed
    {
        return $this->data_conclusao;
    }

    public function setDataConclusao(mixed $data_conclusao = null): void
    {
        $this->data_conclusao = $data_conclusao;
    }
}
