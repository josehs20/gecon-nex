<?php

namespace App\UseCases\Empresa\Requests;

use App\System\Post;

class CriarEmpresaRequest
{
    private string $razao_social;
    private string $nome_fantasia;
    private string $cnpj;
    private int $ativo;
    private int $status_id;
    private ?string $foto;
    private ?string $caixa_cor;
    private ?string $caixa_cor_fundo;
    private ?string $caixa_cor_letras;

    public function __construct(
        string $razao_social,
        string $nome_fantasia,
        string $cnpj,
        int $ativo,
        int $status_id,
        ?string $foto = null,
        ?string $caixa_cor = null,
        ?string $caixa_cor_fundo = null,
        ?string $caixa_cor_letras = null
    ) {
        $this->razao_social = $razao_social;
        $this->nome_fantasia = $nome_fantasia;
        $this->cnpj = $cnpj;
        $this->status_id = $status_id;
        $this->ativo = $ativo ? true : false;
        $this->foto = $foto;
        $this->caixa_cor = $caixa_cor;
        $this->caixa_cor_fundo = $caixa_cor_fundo;
        $this->caixa_cor_letras = $caixa_cor_letras;
    }

    // Getters
    public function getStatusId(): int
    {
        return $this->status_id;
    }

    public function getRazaoSocial(): string
    {
        return $this->razao_social;
    }

    public function getNomeFantasia(): string
    {
        return $this->nome_fantasia;
    }

    public function getCnpj(): string
    {
        return Post::so_numero($this->cnpj);
    }

    public function getAtivo(): int
    {
        return $this->ativo;
    }

    public function getFoto(): ?string
    {
        return $this->foto;
    }

    public function getCaixaCor(): ?string
    {
        return $this->caixa_cor;
    }

    public function getCaixaCorFundo(): ?string
    {
        return $this->caixa_cor_fundo;
    }

    public function getCaixaCorLetras(): ?string
    {
        return $this->caixa_cor_letras;
    }

    // Setters
    public function setRazaoSocial(string $razao_social): void
    {
        $this->razao_social = $razao_social;
    }

    public function setNomeFantasia(string $nome_fantasia): void
    {
        $this->nome_fantasia = $nome_fantasia;
    }

    public function setCnpj(string $cnpj): void
    {
        $this->cnpj = $cnpj;
    }

    public function setAtivo(int $ativo): void
    {
        $this->ativo = $ativo;
    }

    public function setFoto(?string $foto): void
    {
        $this->foto = $foto;
    }

    public function setCaixaCor(?string $caixa_cor): void
    {
        $this->caixa_cor = $caixa_cor;
    }

    public function setCaixaCorFundo(?string $caixa_cor_fundo): void
    {
        $this->caixa_cor_fundo = $caixa_cor_fundo;
    }

    public function setCaixaCorLetras(?string $caixa_cor_letras): void
    {
        $this->caixa_cor_letras = $caixa_cor_letras;
    }
}
