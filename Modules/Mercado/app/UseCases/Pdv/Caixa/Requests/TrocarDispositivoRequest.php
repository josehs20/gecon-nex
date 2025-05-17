<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa\Requests;

use Illuminate\Http\Request;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class TrocarDispositivoRequest extends ServiceUseCase
{
    private string $senha;
    private int $caixa_id;
    private int $usuario_id;
    private Request $request;

    public function __construct(
        CriarHistoricoRequest $historicoRequest,
        string $senha,
        int $caixa_id,
        int $usuario_id,
        Request $request
    ) {
        parent::__construct($historicoRequest);
        $this->senha = $senha;
        $this->caixa_id = $caixa_id;
        $this->usuario_id = $usuario_id;
        $this->request = $request;
    }

    // Getter para senha
    public function getSenha(): string
    {
        return $this->senha;
    }

    // Setter para senha
    public function setSenha(string $senha): void
    {
        $this->senha = $senha;
    }

    // Getter para caixa_id
    public function getCaixaId(): int
    {
        return $this->caixa_id;
    }

    // Setter para caixa_id
    public function setCaixaId(int $caixa_id): void
    {
        $this->caixa_id = $caixa_id;
    }

    // Getter para usuario_id
    public function getUsuarioId(): int
    {
        return $this->usuario_id;
    }

    // Setter para usuario_id
    public function setUsuarioId(int $usuario_id): void
    {
        $this->usuario_id = $usuario_id;
    }

    public function getRequest()
    {
        return $this->request;;
    }

}
