<?php

namespace App\UseCases\Loja\Requests;

class UploadCertificadoRequest
{
    private $loja_id;
    private $arquivo;
    private $senha;
    private $expiracao;
    private $status;

    // Construtor
    public function __construct($loja_id, $arquivo, $senha, $expiracao, $status)
    {
        $this->loja_id = $loja_id;
        $this->arquivo = $arquivo;
        $this->senha = $senha;
        $this->expiracao = $expiracao;
        $this->status = $status;
    }

    // Métodos Getters
    public function getLojaId()
    {
        return $this->loja_id;
    }

    public function getArquivo()
    {
        return $this->arquivo;
    }

    public function getSenha()
    {
        return $this->senha;
    }

    public function getExpiracao()
    {
        return $this->expiracao;
    }

    public function getStatus()
    {
        return $this->status;
    }

    // Métodos Setters
    public function setLojaId($loja_id)
    {
        $this->loja_id = $loja_id;
    }

    public function setArquivo($arquivo)
    {
        $this->arquivo = $arquivo;
    }

    public function setSenha($senha)
    {
        $this->senha = $senha;
    }

    public function setExpiracao($expiracao)
    {
        $this->expiracao = $expiracao;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }
}
