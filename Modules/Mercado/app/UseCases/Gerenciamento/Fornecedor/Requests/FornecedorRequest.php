<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Fornecedor\Requests;

use Modules\Mercado\UseCases\Gerenciamento\Endereco\Requests\EnderecoRequest;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class FornecedorRequest extends ServiceUseCase
{
    private int $empresa_master_cod;
    private string $nome;
    private string $nome_fantasia;
    private string $documento;
    private string $pessoa;
    private bool $ativo;
    private ?string $celular;
    private ?string $telefone_fixo;
    private ?string $email;
    private ?string $site;
    private EnderecoRequest $enderecoRequest;


    public function __construct(
        CriarHistoricoRequest $criarHistoricoRequest,
        int $empresa_master_cod,
        string $nome,
        string $nome_fantasia,
        string $documento,
        string $pessoa,
        bool $ativo,
        ?string $celular = null,
        ?string $telefone_fixo = null,
        ?string $email = null,
        ?string $site = null,
        EnderecoRequest $enderecoRequest
    ) {
        parent::__construct($criarHistoricoRequest);
        $this->empresa_master_cod = $empresa_master_cod;
        $this->nome = $nome;
        $this->nome_fantasia = $nome_fantasia;
        $this->documento = $documento;
        $this->pessoa = $pessoa;
        $this->ativo = $ativo;
        $this->celular = $celular;
        $this->telefone_fixo = $telefone_fixo;
        $this->email = $email;
        $this->site = $site;
        $this->enderecoRequest = $enderecoRequest;
    }

    public function getEmpresaMasterCod(): string
    {
        return $this->empresa_master_cod;
    }

    public function setEmpresaMasterCod(string $empresa_master_cod): void
    {
        $this->empresa_master_cod = $empresa_master_cod;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function getNomeFantasia(): string
    {
        return $this->nome_fantasia;
    }

    public function setNomeFantasia(string $nome_fantasia): void
    {
        $this->nome_fantasia = $nome_fantasia;
    }

    public function getDocumento(): string
    {
        return $this->documento;
    }

    public function setDocumento(string $documento): void
    {
        $this->documento = $documento;
    }

    public function getPessoa(): string
    {
        return $this->pessoa;
    }

    public function setPessoa(string $pessoa): void
    {
        $this->pessoa = $pessoa;
    }

    public function isAtivo(): bool
    {
        return $this->ativo;
    }

    public function setAtivo(bool $ativo): void
    {
        $this->ativo = $ativo;
    }

    public function getCelular(): ?string
    {
        return $this->celular;
    }

    public function setCelular(string $celular = null): void
    {
        $this->celular = $celular;
    }

    public function getTelefoneFixo(): ?string
    {
        return $this->telefone_fixo;
    }

    public function setTelefoneFixo(?string $telefone_fixo): void
    {
        $this->telefone_fixo = $telefone_fixo;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getSite(): ?string
    {
        return $this->site;
    }

    public function setSite(?string $site): void
    {
        $this->site = $site;
    }

    public function getEndereco(): EnderecoRequest
    {
        return $this->enderecoRequest;
    }

    public function setEndereco(EnderecoRequest $enderecoRequest): void
    {
        $this->enderecoRequest = $enderecoRequest;
    }
}
