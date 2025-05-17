<?php

namespace Modules\Mercado\UseCases\Gerenciamento\UnidadeMedida;

use Exception;
use Modules\Mercado\Repository\UnidadeMedida\UnidadeMedidaRepository;
use Modules\Mercado\UseCases\Gerenciamento\UnidadeMedida\Requests\CriarUnidadeMedidaRequest;

class CriarUnidadeMedida
{
    private CriarUnidadeMedidaRequest $request;

    public function __construct(CriarUnidadeMedidaRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        self::validate();

        return self::criarUidadeMedida();
    }

    private function criarUidadeMedida()
    {
        return UnidadeMedidaRepository::create($this->request->getCriarHistoricoRequest(), $this->request->getNome(), $this->request->getSigla(), $this->request->getPodeSerFracionado(), $this->request->getEmpresaId());
    }

    private function validate()
    {
        $existe = UnidadeMedidaRepository::getUnByDescricao($this->request->getNome());

        if ($existe) {
            throw new Exception("Unidade de medida já existe!", 1);
        }
    }
}
