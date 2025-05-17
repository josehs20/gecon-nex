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

    public function __construct(string $razao_social, string $nome_fantasia, string $cnpj, int $ativo, int $status_id)
    {
        $this->razao_social = $razao_social;
        $this->nome_fantasia = $nome_fantasia;
        $this->cnpj = $cnpj;
        $this->status_id = $status_id;
        $this->ativo = $ativo ? true : false;
    }

    public function getStatusId(): int
    {
        return $this->status_id;
    }

    // Métodos Get
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

    // Métodos Set
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
}
