<?php

namespace Modules\Mercado\UseCases\Loja;

use App\Repository\Empresa\EmpresaRepository;
use App\System\Post;
use Exception;
use Modules\Mercado\Repository\Loja\LojaRepository;
use Modules\Mercado\UseCases\Loja\Requests\CriarLojaRequest;

class EditarLoja
{
    private int $id;
    private CriarLojaRequest $request;

    public function __construct(int $id, CriarLojaRequest $request)
    {
        $this->request = $request;
        $this->id = $id;
    }

    public function handle()
    {
        $this->validacoes();

        $empresa = $this->editarLoja();

        return $empresa;
    }

    public function editarLoja()
    {
        return LojaRepository::update($this->id, $this->request->getNome(), $this->request->getEmpresaMasterCod(), $this->request->getLojaMasterCod(), $this->request->getCnpj(), $this->request->getStatusId(), $this->request->getEnderecoId(), $this->request->getCriarHistoricoRequest());
    }

    public function validacoes()
    {
        $empresa = EmpresaRepository::getEmpresaById($this->request->getEmpresaMasterCod());

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
}
