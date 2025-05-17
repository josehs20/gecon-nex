<?php

namespace Modules\Mercado\UseCases\Gerenciamento\ClassificacaoProduto\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class CriarClassificacaoProdutoRequest extends ServiceUseCase
{
    private $nome;
    private $empresa_id;

    // Constructor to initialize properties
    public function __construct(CriarHistoricoRequest $criarHistoricoRequest, $nome, $empresa_id)
    {
        parent::__construct($criarHistoricoRequest);
        $this->nome = $nome;
        $this->empresa_id = $empresa_id;


    }

    // Getters and Setters
    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getEmpresaId()
    {
        return $this->empresa_id;
    }

    public function setEmpresaId($empresa_id)
    {
        $this->empresa_id = $empresa_id;
    }

}
