<?php

namespace App\UseCases\Loja;

use App\Repository\Empresa\EmpresaRepository;
use App\Repository\Loja\LojaRepository;
use App\System\Post;
use App\UseCases\Loja\Requests\CriarLojaRequest;
use Exception;
use Modules\Mercado\Application\EnderecoApplication;
use Modules\Mercado\Application\LojaApplication;
use Modules\Mercado\UseCases\Gerenciamento\Endereco\Requests\EnderecoRequest;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\Loja\Requests\CriarLojaRequest as RequestsCriarLojaRequest;

class AtualizaLoja
{
    private CriarLojaRequest $request;
    private int $id;

    public function __construct(int $id, CriarLojaRequest $request)
    {
        $this->request = $request;
        $this->id = $id;
    }

    public function handle()
    {
        $this->validacoes();
        $loja = $this->atualizaLoja();
        return $loja;
    }

    public function atualizaLoja()
    {
        $loja = LojaRepository::getLojaById($this->id);

        if ($this->request->getMatriz() == true && $loja->matriz == false) {
            EmpresaRepository::updateCnpj($loja->empresa_id, $this->request->getCnpj());
            $lojaMatrizAtual = $loja->empresa->matriz;
            LojaRepository::updateMatriz($lojaMatrizAtual->id, false);
        }

        if (
            $this->request->getMatriz() == true && $loja->cnpj != $this->request->getCnpj()
            || $this->request->getMatriz() == true && $loja->empresa->cnpj != $this->request->getCnpj()
        ) {
            EmpresaRepository::updateCnpj($loja->empresa_id, $this->request->getCnpj());
        }

        $loja = LojaRepository::update($this->id, $this->request->getNome(), $this->request->getEmpresaId(), $this->request->getMatriz(), $this->request->getCnpj(), $this->request->getModuloId(), $this->request->getStatusId());

        $this->editaLojaModulo($loja);
        return $loja;
    }

    public function validacoes()
    {
        $empresa = EmpresaRepository::getEmpresaById($this->request->getEmpresaId());

        if (!$empresa) {
            throw new Exception("Não existe foi possível encontrar a empresa para atualizar essa loja.", 1);
        }

        $validaCnpj = Post::valida_cnpj($this->request->getCnpj());

        if (!$validaCnpj) {
            throw new Exception("CNPJ inválido.", 1);
        }

        $loja = LojaRepository::getLojaById($this->id);
        $novoCnpj = $this->request->getCnpj();
        if ($loja) {
            $lojaExistente = LojaRepository::getLojaByCnpj($novoCnpj);

            if ($lojaExistente && $loja->id != $lojaExistente->id) {
                throw new Exception("A loja com o CNPJ informado já existe.", 1);
            }
        } else {
            throw new Exception("Loja não encontrada para o ID fornecido.", 1);
        }
    }
    private function editaLojaModulo($loja)
    {
        if ($loja->modulo_id == config('config.modulos.mercado')) {
            $endereco_id = null;
            $historico = new CriarHistoricoRequest(
                config('config.processos.empresas.empresa.id'),
                config('config.acoes.atualizou_loja.id'),
                auth()->user()->usuarioMercado->id
            );

            if ($this->request->getEndereco()) {
                if (!$loja->lojaMercado->endereco) {
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
                } else {
                    $endereco_id = EnderecoApplication::atualizarEndereco(new EnderecoRequest(
                        $historico,
                        $this->request->getEndereco()->getLogradouro(),
                        $this->request->getEndereco()->getCidade(),
                        $this->request->getEndereco()->getBairro(),
                        $this->request->getEndereco()->getUf(),
                        $this->request->getEndereco()->getCep(),
                        $this->request->getEndereco()->getNumero(),
                        $this->request->getEndereco()->getComplemento()
                    ), $loja->lojaMercado->endereco_id)->id;
                }
            }

            $lojaModulo = LojaApplication::editarLoja($loja->lojaMercado->id, new RequestsCriarLojaRequest(
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
