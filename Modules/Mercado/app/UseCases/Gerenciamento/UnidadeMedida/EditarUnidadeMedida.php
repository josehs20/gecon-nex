<?php

namespace Modules\Mercado\UseCases\Gerenciamento\UnidadeMedida;

use Exception;
use Modules\Mercado\Repository\UnidadeMedida\UnidadeMedidaRepository;
use Modules\Mercado\UseCases\Gerenciamento\UnidadeMedida\Requests\EditarUnidadeMedidaRequest;

class EditarUnidadeMedida
{
    private EditarUnidadeMedidaRequest $request;

    public function __construct(EditarUnidadeMedidaRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        self::validate();

        return self::editaUidadeMedida();
    }

    private function editaUidadeMedida()
    {
        return UnidadeMedidaRepository::editar($this->request->getCriarHistoricoRequest(),$this->request->getId(), $this->request->getNome(), $this->request->getSigla(), $this->request->getPodeSerFracionado(), $this->request->getEmpresaId());
    }

    private function validate()
    {
        $existe = UnidadeMedidaRepository::getUnByDescricao($this->request->getNome());

        if ($existe && $existe->id != $this->request->getId()) {
            throw new Exception("Unidade de medida já existe!", 1);
        }
    }
}
