<?php

namespace Modules\Mercado\UseCases\Historicos\Requests;

class CriarHistoricoRequest
{

    private int $processo_id;
    private int $acao_id;
    private int $usuario_id;
    private ?int $loja_id;
    private ?string $comentario;

    public function __construct(int $processo_id, int $acao_id, int $usuario_id, string $comentario = null, int $loja_id = null)
    {
        $this->processo_id = $processo_id;
        $this->acao_id = $acao_id;
        $this->usuario_id = $usuario_id;
        $this->comentario = $comentario;
        $this->loja_id = $loja_id;
    }

    public function getProcessoId(): int
    {
        return $this->processo_id;
    }

    public function setProcessoId(int $processo_id): void
    {
        $this->processo_id = $processo_id;
    }

    public function getAcaoId(): int
    {
        return $this->acao_id;
    }

    public function setAcaoId(int $acao_id): void
    {
        $this->acao_id = $acao_id;
    }

    public function getComentario(): ?string
    {
        return $this->comentario;
    }

    public function setComentario(string $comentario = null): void
    {
        $this->comentario = $comentario;
    }

    public function setUsuarioId(int $usuario_id): void
    {
        $this->usuario_id = $usuario_id;
    }

    public function getUsuarioId(): int
    {
        return $this->usuario_id;
    }

    public function getLojaId(): int
    {
        return $this->loja_id;
    }
}
