<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Fornecedor;

use Modules\Mercado\Application\EnderecoApplication;
use Modules\Mercado\Repository\Fornecedor\FornecedorRepository;
use Modules\Mercado\UseCases\Gerenciamento\Endereco\Requests\EnderecoRequest;
use Modules\Mercado\UseCases\Gerenciamento\Fornecedor\Requests\FornecedorRequest;

class CriarFornecedor
{
    private FornecedorRequest $request;

    public function __construct(FornecedorRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $fornecedor = $this->criarFornecedor();
        return $fornecedor;
    }

    public function criarFornecedor()
    {
        $enderecoFornecedor = $this->criarEnderecoFornecedor();
        return FornecedorRepository::create(
            $this->request->getCriarHistoricoRequest(),
            $this->request->getEmpresaMasterCod(),
            $this->request->getNome(),
            $this->request->getNomeFantasia(),
            $this->request->getDocumento(),
            $this->request->getPessoa(),
            $this->request->isAtivo(),
            $this->request->getCelular(),
            $this->request->getTelefoneFixo(),
            $this->request->getEmail(),
            $this->request->getSite(),
            $enderecoFornecedor->id
        );
    }

    public function criarEnderecoFornecedor(){
        return EnderecoApplication::criarEndereco(new EnderecoRequest(
            $this->request->getCriarHistoricoRequest(),
            $this->request->getEndereco()->getLogradouro(),
            $this->request->getEndereco()->getCidade(),
            $this->request->getEndereco()->getBairro(),
            $this->request->getEndereco()->getUf(),
            limparCaracteres($this->request->getEndereco()->getCep()),
            $this->request->getEndereco()->getNumero(),
            $this->request->getEndereco()->getComplemento() == '' ? null : $this->request->getEndereco()->getComplemento(),
        ));
    }
}
