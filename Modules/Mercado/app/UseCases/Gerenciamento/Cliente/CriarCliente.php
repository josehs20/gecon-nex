<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Cliente;

use Modules\Mercado\Application\EnderecoApplication;
use Modules\Mercado\Entities\Cliente;
use Modules\Mercado\Repository\Cliente\ClienteRepository;
use Modules\Mercado\UseCases\Gerenciamento\Cliente\Requests\ClienteRequest;

class CriarCliente
{
    private ClienteRequest $request;

    public function __construct(ClienteRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $this->validade();

        $cliente = $this->criarCliente();
        
        $c = $this->criarCredito($cliente);
        return $cliente;
    }

    private function validade()
    {
        if (!validarDocumento($this->request->getDocumento())) {
            throw new \Exception("Documento inválido", 1);
        }

        if (ClienteRepository::getClienteByDocumento(somenteNumeros($this->request->getDocumento()))) {
            throw new \Exception("Documento já pertence a um cliente", 1);
        }

        if ($this->request->getEmail() && ClienteRepository::getClienteByEmail($this->request->getEmail())) {

            throw new \Exception("Email já pertence a um cliente", 1);
        }

        if ($this->request->getEmail() && filter_var('josehenriuqe.com.bre', FILTER_VALIDATE_EMAIL) !== false) {
            throw new \Exception("Email inválido, certifique-se que o email esta escrito corretamente", 1);
        }
    }

    private function criarCliente()
    {
        $enderecoClienteId = null;
        if ($this->request->getEndereco()) {
            $enderecoClienteId = $this->criarEnderecoCliente()->id;
        }

        return ClienteRepository::create(
            $this->request->getCriarHistoricoRequest(),
            $this->request->getEmpresaMasterCod(),
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
            $enderecoClienteId
        );
    }

    private function criarEnderecoCliente()
    {
        return EnderecoApplication::criarEndereco($this->request->getEndereco());
    }

    private function criarCredito(Cliente $cliente)
    {
        if ($this->request->getLimiteCredito() != null) {
            if (!$cliente->credito) {
                return ClienteRepository::criar_credito($this->request->getCriarHistoricoRequest(), $cliente->id, $this->request->getLimiteCredito());
            } else {
                return ClienteRepository::atualizar_credito($this->request->getCriarHistoricoRequest(),$cliente->id, $this->request->getLimiteCredito());
            }
        }
      
    }
}
