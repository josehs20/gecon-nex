<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Endereco;

use Modules\Mercado\Repository\Endereco\EnderecoRepository;
use Modules\Mercado\UseCases\Gerenciamento\Endereco\Requests\EnderecoRequest;

class CriarEndereco
{
    private EnderecoRequest $request;

    public function __construct(EnderecoRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $endereco = $this->criarEndereco();
        return $endereco;
    }

    public function criarEndereco()
    {
        return EnderecoRepository::create(
            $this->request->getCriarHistoricoRequest(),
            $this->request->getLogradouro(),
            $this->request->getNumero(),
            $this->request->getCidade(),
            $this->request->getBairro(),
            $this->request->getUf(),
            $this->request->getCep(),
            $this->request->getComplemento()
        );
    }
}
