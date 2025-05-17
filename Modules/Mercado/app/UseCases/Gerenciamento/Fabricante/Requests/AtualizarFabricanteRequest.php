<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Fabricante\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class AtualizarFabricanteRequest extends ServiceUseCase
{
    private int $fabricante_id;
    private string $nome;
    private ?string $descricao;
    private string $cnpj;
    private string $razao_social;
    private ?string $inscricao_estadual;
    private ?int $endereco_id;
    private ?string $celular;
    private ?string $telefone;
    private ?string $email;
    private ?string $site;
    private bool $ativo;
    private int $empresa_master_cod;

    public function __construct(
        int $fabricante_id,
        CriarHistoricoRequest $criarHistoricoRequest,
        string $nome,
        ?string $descricao = null,
        string $cnpj,
        string $razao_social,
        ?string $inscricao_estadual = null,
        ?int $endereco_id = null,
        ?string $celular = null,
        ?string $telefone = null,
        ?string $email = null,
        ?string $site = null,
        bool $ativo,
        int $empresa_master_cod
    ) {
        $this->fabricante_id = $fabricante_id;
        parent::__construct($criarHistoricoRequest);
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->cnpj = $cnpj;
        $this->razao_social = $razao_social;
        $this->inscricao_estadual = $inscricao_estadual;
        $this->endereco_id = $endereco_id;
        $this->celular = $celular;
        $this->telefone = $telefone;
        $this->email = $email;
        $this->site = $site;
        $this->ativo = $ativo;
        $this->empresa_master_cod = $empresa_master_cod;
    }

    public function getFabricanteId(): int{
        return $this->fabricante_id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(?string $descricao): void
    {
        $this->descricao = $descricao;
    }

    public function getCnpj(): string
    {
        return $this->cnpj;
    }

    public function setCnpj(string $cnpj): void
    {
        $this->cnpj = $cnpj;
    }

    public function getRazaoSocial(): string
    {
        return $this->razao_social;
    }

    public function setRazaoSocial(string $razao_social): void
    {
        $this->razao_social = $razao_social;
    }

    public function getInscricaoEstadual(): ?string
    {
        return $this->inscricao_estadual;
    }

    public function setInscricaoEstadual(?string $inscricao_estadual): void
    {
        $this->inscricao_estadual = $inscricao_estadual;
    }

    public function getEnderecoId(): ?int
    {
        return $this->endereco_id;
    }

    public function setEndereco(?int $endereco_id): void
    {
        $this->endereco_id = $endereco_id;
    }

    public function getCelular(): ?string
    {
        return $this->celular;
    }

    public function setCelular(?string $celular): void
    {
        $this->celular = $celular;
    }

    public function getTelefone(): ?string
    {
        return $this->telefone;
    }

    public function setTelefone(?string $telefone): void
    {
        $this->telefone = $telefone;
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

    public function isAtivo(): bool
    {
        return $this->ativo;
    }

    public function setAtivo(bool $ativo): void
    {
        $this->ativo = $ativo;
    }

    public function getEmpresaMasterCod(): int
    {
        return $this->empresa_master_cod;
    }

    public function setEmpresaMasterCod(int $empresa_master_cod): void
    {
        $this->empresa_master_cod = $empresa_master_cod;
    }
}
