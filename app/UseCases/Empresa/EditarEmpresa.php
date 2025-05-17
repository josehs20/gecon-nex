<?php

namespace App\UseCases\Empresa;

use App\Repository\Empresa\EmpresaRepository;
use App\System\Post;
use App\UseCases\Empresa\Requests\CriarEmpresaRequest;
use Exception;

class EditarEmpresa
{
    private int $id;
    private CriarEmpresaRequest $request;

    public function __construct(int $id, CriarEmpresaRequest $request)
    {
        $this->request = $request;
        $this->id = $id;
    }

    public function handle()
    {
        $this->validacoes();

        $empresa = $this->editarEmpresa();

        return $empresa;
    }

    public function editarEmpresa()
    {
        return EmpresaRepository::update($this->id, $this->request->getRazaoSocial(), $this->request->getNomeFantasia(), $this->request->getCnpj(), $this->request->getAtivo(), $this->request->getStatusId());
    }

    public function validacoes()
    {
        $empresa = EmpresaRepository::getEmpresaById($this->id);

        if (!$empresa) {
            throw new Exception("Empresa não exste.", 1);
        }

        $validaEmpresa = EmpresaRepository::getEmpresaByCnpj($this->request->getCnpj());

        if ($validaEmpresa && $validaEmpresa->cnpj != $empresa->cnpj) {
            throw new Exception("Empresa já existe.", 1);
        }

        $validaCnpj = Post::valida_cnpj($this->request->getCnpj());

        if (!$validaCnpj) {
            throw new Exception("CNPJ inválido.", 1);
        }
    }
}
