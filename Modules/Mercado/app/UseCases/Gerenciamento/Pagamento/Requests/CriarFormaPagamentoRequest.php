<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Pagamento\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class CriarFormaPagamentoRequest
{
    private string $descricao;
    private bool $ativo;
    private int $especie_pagamento_id;
    private int $loja_id;
    private CriarHistoricoRequest $historico;

    // Construtor
    public function __construct(string $descricao, int $ativo, int $especie_pagamento_id, int $loja_id, CriarHistoricoRequest $historico)
    {
        $this->descricao = $descricao;
        $this->ativo = $ativo;
        $this->especie_pagamento_id = $especie_pagamento_id;
        $this->loja_id = $loja_id;
        $this->historico = $historico;
    }

    // Getters
    public function getDescricao()
    {
        return $this->descricao;
    }

    public function getAtivo()
    {
        return $this->ativo;
    }
    public function getEspeciePagamentoId()
    {
        return $this->especie_pagamento_id;
    }

    public function getLojaId()
    {
        return $this->loja_id;
    }

    public function getCriarHistoricoRequest()
    {
        return $this->historico;
    }
    // Setters
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
    }

    public function setEspeciePagamentoId($especie_pagamento_id)
    {
        $this->especie_pagamento_id = $especie_pagamento_id;
    }

    public function setLojaId($loja_id)
    {
        $this->loja_id = $loja_id;
    }
}
