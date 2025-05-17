<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Produto\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class EditarProdutoRequest extends CriarProdutoRequest
{
    private int $id;

    // Constructor to initialize properties
    public function __construct(
        $id,
        $nome,
        $preco_custo,
        $preco_venda,
        $cod_barras,
        $cod_aux,
        $unidade_medida,
        $classificacao_id,
        $lojas,
        $data_validade,
        $fabricante_id,
        $descricao,
        CriarHistoricoRequest $criarHistoricoRequest
    ) {
        $this->id = $id;
        parent::__construct(
            $nome,
            $preco_custo,
            $preco_venda,
            $cod_barras,
            $cod_aux,
            $unidade_medida,
            $classificacao_id,
            $lojas,
            $data_validade,
            $fabricante_id,
            $descricao,
            $criarHistoricoRequest
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
}
