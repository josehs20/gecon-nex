<?php

namespace App\UseCases\Loja;

use App\Repository\Loja\LojaRepository;
use App\System\Post;
use App\UseCases\Loja\Requests\CriarLojaRequest;
use Exception;
use Modules\Mercado\Application\EnderecoApplication;
use Modules\Mercado\Application\LojaApplication;
use Modules\Mercado\UseCases\Gerenciamento\Endereco\Requests\EnderecoRequest;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\Loja\Requests\CriarLojaRequest as RequestsCriarLojaRequest;

class CriarLoja
{
    private CriarLojaRequest $request;

    public function __construct(CriarLojaRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $this->validacoes();

        $loja = $this->criarLoja();

        return $loja;
    }

    public function validacoes()
    {
        $empresaExiste = LojaRepository::getLojaByCnpj($this->request->getCnpj());
        if ($empresaExiste) {
            throw new Exception("Loja com cnpj" . $this->request->getCnpj() . " já existe.", 1);
        }

        $validaCnpj = Post::valida_cnpj($this->request->getCnpj());

        if (!$validaCnpj) {
            throw new Exception("CNPJ inválido.", 1);
        }
    }

    public function criarLoja()
    {
        $loja = LojaRepository::create(
            $this->request->getNome(),
            $this->request->getEmpresaId(),
            $this->request->getMatriz(),
            $this->request->getCnpj(),
            $this->request->getModuloId(),
            $this->request->getStatusId(),
            $this->request->getEmail(),
            $this->request->getTelefone()
        );

        $this->criaLojaModulo($loja);
        return $loja;
    }

    private function criaLojaModulo($loja)
    {
        if ($loja->modulo_id == config('config.modulos.mercado')) {
            $endereco_id = null;
            $historico = new CriarHistoricoRequest(
                config('config.processos.empresas.empresa.id'),
                config('config.acoes.cadastrou_loja.id'),
                auth()->user()->usuarioMercado->id
            );
            if ($this->request->getEndereco()) {
                $endereco_id = EnderecoApplication::criarEndereco(new EnderecoRequest(
                    $historico,
                    $this->request->getEndereco()->getLogradouro(),
                    $this->request->getEndereco()->getCidade(),
                    $this->request->getEndereco()->getBairro(),
                    $this->request->getEndereco()->getUf(),
                    $this->request->getEndereco()->getCep(),
                    $this->request->getEndereco()->getNumero(),
                    $this->request->getEndereco()->getComplemento()
                ))->id;
            }

            $lojaModulo = LojaApplication::criarLoja(new RequestsCriarLojaRequest(
                $loja->nome,
                $loja->empresa_id,
                $loja->id,
                $loja->cnpj,
                $loja->status_id,
                $endereco_id,
                $historico
            ));
        }
    }
}
