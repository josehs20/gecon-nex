<?php

namespace Modules\Mercado\UseCases\Loja;

use App\Repository\Empresa\EmpresaRepository;
use App\System\Post;
use Exception;
use Modules\Mercado\Application\PagamentoApplication;
use Modules\Mercado\Repository\Loja\LojaRepository;
use Modules\Mercado\UseCases\Gerenciamento\Pagamento\Requests\CriarFormaPagamentoRequest;
use Modules\Mercado\UseCases\Loja\Requests\CriarLojaRequest;

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
        $this->criaFormaPagamentoPadrao($loja);

        return $loja;
    }

    public function criarLoja()
    {
        return LojaRepository::create($this->request->getNome(), $this->request->getEmpresaMasterCod(), $this->request->getLojaMasterCod(), $this->request->getCnpj(), $this->request->getStatusId(), $this->request->getEnderecoId(), $this->request->getCriarHistoricoRequest());
    }

    public function validacoes()
    {
        $empresa = EmpresaRepository::getEmpresaById($this->request->getEmpresaMasterCod());

        if (!$empresa) {
            throw new Exception("Não existe foi possível encontrar a empresa para cadastrar essa loja.", 1);
        }

        $validaCnpj = Post::valida_cnpj($this->request->getCnpj());

        if (!$validaCnpj) {
            throw new Exception("CNPJ inválido.", 1);
        }

        $lojaExistente = LojaRepository::getLojaByCnpj($this->request->getCnpj());

        if ($lojaExistente) {
            throw new Exception("A loja com o CNPJ informado já existe.", 1);
        }
    }

    public function criaFormaPagamentoPadrao($loja)
    {
        $especie = config('config.especie_pagamento.dinheiro.id');
        $descricao = config('config.especie_pagamento.dinheiro.nome');
        $especies = config('config.especie_pagamento');
        foreach ($especies as $key => $e) {
            $descricao = $e['nome'];
            $ativo = true;
            $especie = $e['id'];
            PagamentoApplication::criarFormaPagamento(new CriarFormaPagamentoRequest($descricao, $ativo, $especie,$loja->id, $this->request->getCriarHistoricoRequest()));
        }
    }
}
