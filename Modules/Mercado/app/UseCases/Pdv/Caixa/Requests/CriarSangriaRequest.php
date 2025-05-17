<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa\Requests;

use Illuminate\Http\Request;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class CriarSangriaRequest extends ServiceUseCase
{
    private int $caixa_id;
    private string $senha;
    private ?string $descricao;
    private ?int $valorSangria;
    private Request $request;

    // Construtor para inicializar os campos
    public function __construct(CriarHistoricoRequest $historicoRequest, int $caixa_id, string $senha, ?int $valorSangria = null, ?string $descricao = null,Request $request)
    {
        parent::__construct($historicoRequest);
        $this->caixa_id = $caixa_id;
        $this->senha = $senha;
        $this->valorSangria = $valorSangria;
        $this->descricao = $descricao;
        $this->request = $request;
    }

    // Método get para caixa_id
    public function getCaixaId(): int
    {
        return $this->caixa_id;
    }

    // Método set para caixa_id
    public function setCaixaId(int $caixa_id): void
    {
        $this->caixa_id = $caixa_id;
    }

    // Método get para senha
    public function getSenha(): string
    {
        return $this->senha;
    }

    // Método set para senha
    public function setSenha(string $senha): void
    {
        $this->senha = $senha;
    }

    public function getValorSangria(): ?int
    {
        return $this->valorSangria;
    }

    public function setValorSangria(?int $valorSangria = null): void
    {
        $this->valorSangria = $valorSangria;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(?string $descricao = null): void
    {
        $this->descricao = $descricao;
    }
    // Método get para acessar a instância de Request
    public function getRequest(): Request
    {
        return $this->request;
    }
}
