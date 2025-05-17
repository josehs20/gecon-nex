<?php

namespace App\UseCases\Empresa;

use App\Repository\Empresa\EmpresaRepository;
use App\System\Post;
use App\UseCases\Empresa\Requests\CriarEmpresaRequest;
use Exception;

class CriarEmpresa
{
    private CriarEmpresaRequest $request;

    public function __construct(CriarEmpresaRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $this->validacoes();

        $empresa = $this->criarEmpresa();

        return $empresa;
    }

    public function criarEmpresa()
    {
        return EmpresaRepository::create($this->request->getRazaoSocial(), $this->request->getNomeFantasia(), $this->request->getCnpj(), $this->request->getAtivo(), $this->request->getStatusId());
    }

    public function validacoes()
    {
        $empresaExiste = EmpresaRepository::getEmpresaByCnpj($this->request->getCnpj());
        if ($empresaExiste) {
            throw new Exception("Empresa já existe.", 1);
        }

        $validaCnpj = Post::valida_cnpj($this->request->getCnpj());

        if (!$validaCnpj) {
            throw new Exception("CNPJ inválido.", 1);
        }
    }
}
