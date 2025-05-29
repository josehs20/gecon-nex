<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Fabricante;

use Exception;
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
        $this->validate();
        return $this->criar();
    }

    private function validate() {
        $fabricante = FabricanteRepository::getFabricantePorCnpj($this->request->getCnpj(), auth()->user()->empresa_id);
        if ($fabricante) {
            throw new Exception("Fabricante com cnpj:". $fabricante->cnpj . ' já existe.', 1);
        }
    }

    private function criar()
    {
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
