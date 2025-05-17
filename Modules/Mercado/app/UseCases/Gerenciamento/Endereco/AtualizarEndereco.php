<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Endereco;

use Modules\Mercado\Repository\Endereco\EnderecoRepository;
use Modules\Mercado\UseCases\Gerenciamento\Endereco\Requests\EnderecoRequest;

class AtualizarEndereco
{
    private EnderecoRequest $request;
    private int $enderecoId;

    public function __construct(EnderecoRequest $request, int $enderecoId)
    {
        $this->request = $request;
        $this->enderecoId = $enderecoId;
    }

    public function handle()
    {
        $endereco = $this->atualizarEndereco();
        return $endereco;
    }

    public function atualizarEndereco()
    {
        return EnderecoRepository::update(
            $this->enderecoId,
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
