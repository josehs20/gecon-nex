<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class CriarEvidenciaRequest extends ServiceUseCase
{
    protected int $caixa_id;
    protected int $acao_id;
    protected int $usuario_id;
    protected ?int $valor_total;
    protected ?int $valor_dinheiro;
    protected ?int $caixa_recurso_id;
    protected ?string $descricao;

    public function __construct(
        CriarHistoricoRequest $criarHistoricoRequest,
        int $caixa_id,
        int $acao_id,
        int $usuario_id,
        int $caixa_recurso_id,
        ?int $valor_total = null,
        ?int $valor_dinheiro = null,
        ?string $descricao = null
    ) {
        parent::__construct($criarHistoricoRequest);

        $this->caixa_id = $caixa_id;
        $this->acao_id = $acao_id;
        $this->usuario_id = $usuario_id;
        $this->valor_total = $valor_total;
        $this->valor_dinheiro = $valor_dinheiro;
        $this->caixa_recurso_id = $caixa_recurso_id;
        $this->descricao = $descricao;
    }

    public function getCaixaId()
    {
        return $this->caixa_id;
    }

    public function setCaixaId($caixa_id)
    {
        $this->caixa_id = $caixa_id;
    }

    public function getAcaoId()
    {
        return $this->acao_id;
    }

    public function setAcaoId($acao_id)
    {
        $this->acao_id = $acao_id;
    }

    public function getUsuarioId()
    {
        return $this->usuario_id;
    }

    public function setUsuarioId($usuario_id)
    {
        $this->usuario_id = $usuario_id;
    }

    public function getValorTotal()
    {
        return $this->valor_total;
    }

    public function setValorTotal($valor_total)
    {
        $this->valor_total = $valor_total;
    }

    public function getValorDinheiro()
    {
        return $this->valor_dinheiro;
    }

    public function setValorDinheiro($valor_dinheiro)
    {
        $this->valor_dinheiro = $valor_dinheiro;
    }

    public function getCaixaRecursoId()
    {
        return $this->caixa_recurso_id;
    }

    public function setCaixaRecursoId($caixa_recurso_id)
    {
        $this->caixa_recurso_id = $caixa_recurso_id;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }
}
