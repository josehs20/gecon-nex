<?php

namespace Modules\Mercado\UseCases\Gerenciamento\ClassificacaoProduto\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class UpdateClassificacaoProdutoRequest extends ServiceUseCase
{
    private $id;
    private $nome;
    private $empresa_id;

    // Constructor to initialize properties
    public function __construct(CriarHistoricoRequest $criarHistoricoRequest, $id, $nome, $empresa_id)
    {
        parent::__construct($criarHistoricoRequest);
        $this->id = $id;
        $this->nome = $nome;
        $this->empresa_id = $empresa_id;
    }

    // Getter and Setter for 'id'
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    // Getter and Setter for 'nome'
    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    // Getter and Setter for 'empresa_id'
    public function getEmpresaId()
    {
        return $this->empresa_id;
    }

    public function setEmpresaId($empresa_id)
    {
        $this->empresa_id = $empresa_id;
    }
}
