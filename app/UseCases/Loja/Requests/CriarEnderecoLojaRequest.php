<?php

namespace App\UseCases\Loja\Requests;

class CriarEnderecoLojaRequest
{
    private string $logradouro;
    private ?string $numero;
    private string $cidade;
    private string $bairro;
    private string $uf;
    private string $cep;
    private ?string $complemento;

    // Construtor
    public function __construct(
        string $logradouro,
        string $cidade,
        string $bairro,
        string $uf,
        string $cep,
        ?string $numero = null,
        ?string $complemento = null // Valor padrão como nulo
    ) {
        $this->logradouro = $logradouro;
        $this->numero = $numero;
        $this->cidade = $cidade;
        $this->bairro = $bairro;
        $this->uf = $uf;
        $this->cep = $cep;
        $this->complemento = $complemento;
    }

    // Getters
    public function getLogradouro(): string
    {
        return $this->logradouro;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function getCidade(): string
    {
        return $this->cidade;
    }

    public function getBairro(): string
    {
        return $this->bairro;
    }

    public function getUf(): string
    {
        return $this->uf;
    }

    public function getCep(): string
    {
        return $this->cep;
    }

    public function getComplemento(): ?string
    {
        return $this->complemento;
    }

    // Setters
    public function setLogradouro(string $logradouro): void
    {
        $this->logradouro = $logradouro;
    }

    public function setNumero(string $numero = null): void
    {
        $this->numero = $numero;
    }

    public function setCidade(string $cidade): void
    {
        $this->cidade = $cidade;
    }

    public function setBairro(string $bairro): void
    {
        $this->bairro = $bairro;
    }

    public function setUf(string $uf): void
    {
        $this->uf = $uf;
    }

    public function setCep(string $cep): void
    {
        $this->cep = $cep;
    }

    public function setComplemento(?string $complemento): void
    {
        $this->complemento = $complemento;
    }
}
