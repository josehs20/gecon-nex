<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Fornecedor;

use Modules\Mercado\Repository\Fornecedor\FornecedorRepository;

class GetTodosFornecedoresPorAtivo
{
    private bool $ativo;

    public function __construct($ativo)
    {
        $this->ativo = $ativo;
    }

    public function handle()
    {
        $fornecedores = $this->getTodosFornecedoresPorAtivo();
        return $fornecedores;
    }

    public function getTodosFornecedoresPorAtivo()
    {
        return FornecedorRepository::getTodosFornecedoresPorAtivo($this->ativo);
    }
}
