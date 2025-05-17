<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Fornecedor;

use Modules\Mercado\Repository\Fornecedor\FornecedorRepository;

class GetFornecedorById
{
    private int $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function handle()
    {
        $fornecedor = $this->getFornecedorById();
        return $fornecedor;
    }

    public function getFornecedorById()
    {
        return FornecedorRepository::getFornecedorById($this->id);
    }
}
