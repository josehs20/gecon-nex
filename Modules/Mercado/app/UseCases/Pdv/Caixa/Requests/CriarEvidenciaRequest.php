<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa\Requests;

use Illuminate\Http\Request;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class CriarEvidenciaRequest extends ServiceUseCase
{
    private Request $request;
    private int $caixa_id;
    private int $usuario_id;
    private int $valor_abertura;
    private ?int $valor_fechamento;
    private ?int $valor_sangria;
    private ?string $descricao;

    // Construtor para inicializar os campos
    public function __construct(Request $request, CriarHistoricoRequest $criarHistoricoRequest, int $caixa_id, int $usuario_id, int $valor_abertura, int $valor_fechamento =null, int $valor_sangria =null, ?string $descricao =null)
    {
        parent::__construct($criarHistoricoRequest);
        $this->request = $request;
        $this->caixa_id = $caixa_id;
        $this->usuario_id = $usuario_id;
        $this->valor_abertura = $valor_abertura;
        $this->valor_fechamento = $valor_fechamento;
        $this->valor_sangria = $valor_sangria;
        $this->descricao = $descricao;

    }

    // Getter e Setter para Request
    public function getRequest(): Request
    {
        return $this->request;
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    // Getter e Setter para caixa_id
    public function getCaixaId(): int
    {
        return $this->caixa_id;
    }

    public function setCaixaId(int $caixa_id): void
    {
        $this->caixa_id = $caixa_id;
    }

    // Getter e Setter para usuario_id
    public function getUsuarioId(): int
    {
        return $this->usuario_id;
    }

    public function setUsuarioId(int $usuario_id): void
    {
        $this->usuario_id = $usuario_id;
    }

    public function getValorAbertura(): int
    {
        return $this->valor_abertura;
    }

    public function setValorAbertura(float $valor_abertura): void
    {
        $this->valor_abertura = converterParaCentavos($valor_abertura);
    }

    public function getValorFechamento(): ?int
    {
        return $this->valor_fechamento;
    }

    public function setValorFechamento(float $valor_fechamento): void
    {
        $this->valor_fechamento = converterParaCentavos($valor_fechamento);
    }

    public function getValorSangria(): ?int
    {
        return $this->valor_sangria;
    }

    public function setValorSangria(float $valor_sangria): void
    {
        $this->valor_sangria = converterParaCentavos($valor_sangria);
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }
    public function setDescricao(?string $descricao = null): void
    {
        $this->descricao = $descricao;
    }
}