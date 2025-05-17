<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Estoque\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class UpdateEstoqueRequest extends CriarEstoqueRequest
{
    private int $id;

    // Construtor
    public function __construct(
        $id,
        $custo,
        $preco,
        $produto_id,
        $loja_id,
        $quantidade_total = null,
        $quantidade_disponivel = null,
        $quantidade_minima = null,
        $quantidade_maxima = null,
        $localizacao = null,
        CriarHistoricoRequest $historicoRequest
    ) {
        $this->id = $id;
        parent::__construct($custo, $preco, $produto_id, $loja_id, $quantidade_total, $quantidade_disponivel, $quantidade_minima, $quantidade_maxima, $localizacao, $historicoRequest);
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }
}
