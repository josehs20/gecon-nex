<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Fabricante;

use Exception;
use Modules\Mercado\Application\EnderecoApplication;
use Modules\Mercado\Repository\Fabricante\FabricanteRepository;
use Modules\Mercado\UseCases\Gerenciamento\Endereco\Requests\EnderecoRequest;
use Modules\Mercado\UseCases\Gerenciamento\Fabricante\Requests\AtualizarFabricanteRequest;

class AtualizarFabricante
{
    private AtualizarFabricanteRequest $request;

    public function __construct(AtualizarFabricanteRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {

        $this->validate();
        return $this->atualizar();
    }

    private function validate() {
        $fabricante = FabricanteRepository::getFabricantePorCnpj($this->request->getCnpj(), auth()->user()->empresa_id);
        if (!$fabricante) {
            throw new Exception("Fabricante não existe cadastrado em sua empresa.", 1);
        }
    }
    private function atualizar(){
        return FabricanteRepository::atualizar(
            $this->request->getFabricanteId(),
            $this->request->getNome(),
            $this->request->getDescricao(),
            $this->request->getCnpj(),
            $this->request->getRazaoSocial(),
            $this->request->getInscricaoEstadual(),
            $this->request->getEnderecoId(),
            $this->request->getCelular(),
            $this->request->getTelefone(),
            $this->request->getEmail(),
            $this->request->getSite(),
            $this->request->isAtivo(),
            $this->request->getEmpresaMasterCod()
        );
    }

}
