<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Caixa\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class AtualizarCaixaRequest extends CriarCaixaRequest
{
    private int $id;
    private bool $ativo;

    public function __construct(CriarHistoricoRequest $historicoRequest, int $id, bool $ativo, string $nome, int $status_id)
    {
        parent::__construct($historicoRequest, $nome, $status_id, []);
        $this->id = $id;
        $this->ativo = $ativo;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

      public function getAtivo()
    {
        return $this->ativo;
    }

    public function setAtivo(bool $ativo)
    {
        $this->id = $ativo;
    }

}
