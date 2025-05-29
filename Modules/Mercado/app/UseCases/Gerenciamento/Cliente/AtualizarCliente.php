<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Cliente;

use Modules\Mercado\Application\EnderecoApplication;
use Modules\Mercado\Entities\Cliente;
use Modules\Mercado\Repository\Cliente\ClienteRepository;
use Modules\Mercado\Repository\Endereco\EnderecoRepository;
use Modules\Mercado\UseCases\Gerenciamento\Cliente\Requests\ClienteRequest;

class AtualizarCliente
{
    private ClienteRequest $request;
    private int $clienteId;

    public function __construct(ClienteRequest $request, int $clienteId)
    {
        $this->request = $request;
        $this->clienteId = $clienteId;
    }

    public function handle()
    {
        $cliente = $this->atualizarCliente();
        $this->atualizarEnderecoCliente($cliente);
        $this->criarOrAtualizaCredito($cliente);
        return $cliente;
    }

    public function atualizarCliente()
    {
        return ClienteRepository::update(
            $this->request->getCriarHistoricoRequest(),
            $this->request->getEmpresaMasterCod(),
            $this->clienteId,
            $this->request->getNome(),
            $this->request->getDocumento(),
            $this->request->getPessoa(),
            $this->request->isAtivo(),
            $this->request->getStatus(),
            $this->request->getCelular(),
            $this->request->getTelefoneFixo(),
            $this->request->getEmail(),
            $this->request->getDataNascimento(),
            $this->request->getObservacao(),
            $this->getEnderecoClienteId()
        );
    }
    public function atualizarEnderecoCliente($cliente){
        if (!$cliente->endereco_id) {
            return EnderecoApplication::criarEndereco($this->request->getEndereco());
        }else {
            return EnderecoApplication::atualizarEndereco($this->request->getEndereco(), $cliente->endereco_id);
        }
    }

    public function getEnderecoClienteId(){
        $cliente =  ClienteRepository::getClienteById($this->clienteId);
        return $cliente->endereco_id;
    }

    private function criarOrAtualizaCredito(Cliente $cliente)
    {
        if (!$cliente->credito) {
            return ClienteRepository::criar_credito($this->request->getCriarHistoricoRequest(), $cliente->id, $this->request->getLimiteCredito());
        } else {
            return ClienteRepository::atualizar_credito($this->request->getCriarHistoricoRequest(), $cliente->id, $this->request->getLimiteCredito());
        }
    }
}
