<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Modules\Mercado\Repository\PDV\CaixaPDVRepository;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\CriarCaixaDiarioRequest;

class CriarOuAtualizaCaixaDiario
{
    private CriarCaixaDiarioRequest $request;
    private ?int $id;
    public function __construct(CriarCaixaDiarioRequest $request, ?int $id = null)
    {
        $this->request = $request;
        $this->id = $id;
    }

    public function handle()
    {
        $this->validate();
        if ($this->id) {
            $this->atualizaCaixaDiario();
        } else {
            return $this->criaCaixaDiario();
        }
    }

    public function validate() {}

    public function criaCaixaDiario()
    {
        return CaixaPDVRepository::criarAttrsCaixaDiario($this->request->getCriarHistoricoRequest(), [
            'caixa_id' => $this->request->getCaixaId(),
            'caixa_evidencia_id' => $this->request->getCaixaEvidenciaId(),
            'usuario_id' => $this->request->getUsuarioId(),
            'loja_id' => $this->request->getLojaId(),
            'status_id' => $this->request->getStatusId(),
            'data_abertura' => $this->request->getDataAbertura(),
        ]);
    }

    public function atualizaCaixaDiario() {}
}
