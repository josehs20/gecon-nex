<?php

namespace App\UseCases\Usuario;

use App\Repository\Usuario\UsuarioRepository;
use App\UseCases\Usuario\Requests\UsuarioRequest;

class AtualizarUsuario
{
    private UsuarioRequest $request;

    public function __construct(UsuarioRequest $request)
    {
       $this->request = $request;
    }

    public function handle()
    {
        return $this->atualizarUsuario();
    }

    private function atualizarUsuario(){
        return UsuarioRepository::atualizar(
            $this->request->getUserId(),
            $this->request->getName(),
            $this->request->getLogin(),
            $this->request->getEmail(),
            $this->request->getModuloId(),
            $this->request->getPermiteAbrirCaixa(),
            $this->request->getTipoUsuarioId(),
            $this->request->getEmpresaId(),
            $this->request->getLojaId(),
            $this->request->getEnderecoId(),
            $this->request->getStatusId(),
            dataStringParaDataBancoDeDados($this->request->getDataNascimento()),
            $this->request->getDocumento(),
            $this->request->getTelefone(),
            $this->request->getCelular(),
            $this->request->isAtivo(),
            dataStringParaDataBancoDeDados($this->request->getDataAdmissao()),
            $this->request->getSalario(),
            $this->request->getTipoContrato(),
            dataStringParaDataBancoDeDados($this->request->getDataDemissao()),
            $this->request->getComissao(),
            $this->request->getCriarHistoricoRequest()
        );    
    }

}
