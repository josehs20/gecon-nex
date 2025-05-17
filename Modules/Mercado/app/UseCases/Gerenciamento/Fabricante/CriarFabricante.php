<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Fabricante;

use Modules\Mercado\Repository\Fabricante\FabricanteRepository;
use Modules\Mercado\UseCases\Gerenciamento\Fabricante\Requests\CriarFabricanteRequest;

class CriarFabricante
{
    private CriarFabricanteRequest $request;

    public function __construct(CriarFabricanteRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        return $this->criar();
    }

    private function criar(){
        return FabricanteRepository::criar(            
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
