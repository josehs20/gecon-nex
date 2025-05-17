<?php

namespace App\UseCases\Loja\Requests;

class CriarLojaRequest
{
    private $nome;
    private $empresa_id;
    private $matriz;
    private $cnpj;
    private $modulo_id;
    private $status_id;
    private $endereco;
    private $email;
    private $telefone;


    public function __construct($nome, $empresa_id, $matriz, $cnpj, $modulo_id, $status_id, $email = null, $telefone = null, CriarEnderecoLojaRequest $endereco = null)
    {
        $this->nome = $nome;
        $this->empresa_id = $empresa_id;
        $this->matriz = $matriz;
        $this->cnpj = $cnpj;
        $this->modulo_id = $modulo_id;
        $this->status_id = $status_id;
        $this->endereco = $endereco;
        $this->email = $email;
        $this->telefone = $telefone;
    }
    public function getEmail()
    {
        return $this->email;
    }
    
    public function getTelefone()
    {
        return $this->telefone;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getEmpresaId()
    {
        return $this->empresa_id;
    }

    public function setEmpresaId($empresa_id)
    {
        $this->empresa_id = $empresa_id;
    }

    public function getMatriz()
    {
        return $this->matriz;
    }

    public function setMatriz($matriz)
    {
        $this->matriz = $matriz;
    }

    public function getCnpj()
    {
        return $this->cnpj;
    }

    public function setCnpj($cnpj)
    {
        $this->cnpj = $cnpj;
    }

    public function getModuloId()
    {
        return $this->modulo_id;
    }

    public function setModuloId($modulo_id)
    {
        $this->modulo_id = $modulo_id;
    }

    public function getStatusId()
    {
        return $this->status_id;
    }

    public function setStatusId($status_id)
    {
        $this->status_id = $status_id;
    }

    public function getEndereco()
    {
        return $this->endereco;
    }
}
