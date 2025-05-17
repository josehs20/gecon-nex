<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Fabricante;

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
        return $this->atualizar();
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
