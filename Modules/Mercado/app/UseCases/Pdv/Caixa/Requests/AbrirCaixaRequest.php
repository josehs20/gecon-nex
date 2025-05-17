<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa\Requests;

use Illuminate\Http\Request;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class AbrirCaixaRequest extends ServiceUseCase
{
    private int $loja_id;
    private int $valorInicial;
    private string $senha;
    private int $usuario_id;
    private Request $request;

    public function __construct(CriarHistoricoRequest $historicoRequest, float $valorInicial, string $senha, int $loja_id, int $usuario_id, Request $request)
    {
        parent::__construct($historicoRequest);
        $this->loja_id = $loja_id;
        $this->valorInicial = $valorInicial;
        $this->senha = $senha;
        $this->usuario_id = $usuario_id;
        $this->request = $request;
    }

    public function getCaixaId()
    {
        return $this->loja_id;
    }

    public function setCaixaId(int $loja_id)
    {
        $this->loja_id = $loja_id;
    }

    public function getSenha()
    {
        return $this->senha;
    }

    public function setSenha(string $senha)
    {
        $this->senha = $senha;
    }

    public function getValorInicial()
    {
        return $this->valorInicial;
    }

    public function setValorInicial(float $valorInicial)
    {
        $this->valorInicial = converterParaCentavos($valorInicial);
    }

    public function getUsuarioId()
    {
        return $this->usuario_id;
    }

    public function setUsuarioId(int $usuario_id)
    {
        $this->usuario_id = $usuario_id;
    }

    public function getCriarEvidenciaRequest()
    {
        return new CriarEvidenciaRequest($this->request, $this->getCriarHistoricoRequest(), $this->getCaixaId(), $this->getUsuarioId(), $this->valorInicial, null, null, $this->getCriarHistoricoRequest()->getComentario());
    }
}
