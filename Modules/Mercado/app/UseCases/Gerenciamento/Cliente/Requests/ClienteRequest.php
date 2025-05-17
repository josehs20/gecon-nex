<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Cliente\Requests;

use Modules\Mercado\UseCases\Gerenciamento\Endereco\Requests\EnderecoRequest;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class ClienteRequest extends ServiceUseCase
{
    private CriarHistoricoRequest $historico;
    private int $empresa_master_cod;
    private string $nome;
    private string $documento;
    private string $pessoa;
    private bool $ativo;
    private int $status;
    private ?string $celular;
    private ?string $telefone_fixo;
    private ?string $email;
    private ?string $data_nascimento;
    private ?int $limite_credito;
    private ?string $observacao;
    private ?EnderecoRequest $enderecoRequest;

    public function __construct(
        CriarHistoricoRequest $historico,
        int $empresa_master_cod,
        string $nome,
        string $documento,
        string $pessoa,
        bool $ativo,
        int $status,
        ?string $celular = null,
        ?string $telefone_fixo = null,
        ?string $email = null,
        ?string $data_nascimento = null,
        ?int $limite_credito = null,
        ?string $observacao = null,
        EnderecoRequest $enderecoRequest = null
    ) {
        parent::__construct($historico);
        $this->empresa_master_cod = $empresa_master_cod;
        $this->nome = $nome;
        $this->documento = $documento;
        $this->pessoa = $pessoa;
        $this->ativo = $ativo;
        $this->status = $status;
        $this->celular = $celular;
        $this->telefone_fixo = $telefone_fixo;
        $this->email = $email;
        $this->data_nascimento = $data_nascimento;
        $this->limite_credito = $limite_credito;
        $this->observacao = $observacao;
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

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getCelular(): ?string
    {
        return $this->celular;
    }

    public function setCelular(?string $celular): void
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

    public function getDataNascimento(): ?string
    {
        return $this->data_nascimento;
    }

    public function setDataNascimento(?string $data_nascimento): void
    {
        $this->data_nascimento = $data_nascimento;
    }

    public function getLimiteCredito(): ?int
    {
        return $this->limite_credito;
    }

    public function setLimiteCredito(?int $limite_credito): void
    {
        $this->limite_credito = $limite_credito;
    }

    public function getObservacao(): ?string
    {
        return $this->observacao;
    }

    public function setObservacao(?string $observacao): void
    {
        $this->observacao = $observacao;
    }

    public function getEndereco(): ?EnderecoRequest
    {
        return $this->enderecoRequest;
    }

    public function setEndereco(EnderecoRequest $enderecoRequest): void
    {
        $this->enderecoRequest = $enderecoRequest;
    }
}
