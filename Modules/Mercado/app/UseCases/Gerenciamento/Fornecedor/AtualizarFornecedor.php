<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Fornecedor;

use Modules\Mercado\Application\EnderecoApplication;
use Modules\Mercado\Entities\Endereco;
use Modules\Mercado\Repository\Endereco\EnderecoRepository;
use Modules\Mercado\Repository\Fornecedor\FornecedorRepository;
use Modules\Mercado\UseCases\Gerenciamento\Fornecedor\Requests\FornecedorRequest;

class AtualizarFornecedor
{
    private FornecedorRequest $request;
    private int $fornecedorId;

    public function __construct(FornecedorRequest $request, int $fornecedorId)
    {
        $this->request = $request;
        $this->fornecedorId = $fornecedorId;
    }

    public function handle()
    {
        $fornecedor = $this->atualizarFornecedor();
        $this->atualizarEnderecoFornecedor($fornecedor);
        return $fornecedor;
    }

    public function atualizarFornecedor()
    {
        return FornecedorRepository::update(
            $this->request->getCriarHistoricoRequest(),
            $this->request->getEmpresaMasterCod(),
            $this->fornecedorId,
            $this->request->getNome(),
            $this->request->getNomeFantasia(),
            $this->request->getDocumento(),
            $this->request->getPessoa(),
            $this->request->isAtivo(),
            $this->request->getCelular(),
            $this->request->getTelefoneFixo(),
            $this->request->getEmail(),
            $this->request->getSite(),
            $this->getEnderecoFornecedorId()
        );
    }
    public function atualizarEnderecoFornecedor($fornecedor)
    {

        if (!$fornecedor->endereco_id) {
            return EnderecoApplication::criarEndereco($this->request->getEndereco());
        }

        return EnderecoApplication::atualizarEndereco($this->request->getEndereco(), $fornecedor->endereco_id);
    }

    public function getEnderecoFornecedorId()
    {
        $fornecedor =  FornecedorRepository::getFornecedorById($this->fornecedorId);
        return $fornecedor->endereco_id;
    }
}
