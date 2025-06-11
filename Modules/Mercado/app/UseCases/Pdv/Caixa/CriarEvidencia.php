<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Modules\Mercado\Repository\PDV\CaixaPDVRepository;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\CriarEvidenciaRequest;

class CriarEvidencia
{
    private CriarEvidenciaRequest $request;
    public function __construct(CriarEvidenciaRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        return $this->criarEvidencias();
    }

    private function criarEvidencias()
    {
        return CaixaPDVRepository::criarCriarEvidenciaAttrs($this->request->getCriarHistoricoRequest(), [
            'caixa_id' => $this->request->getCaixaId(),
            'acao_id' => $this->request->getAcaoId(),
            'usuario_id' => $this->request->getUsuarioId(),
            'valor_total' => $this->request->getValorTotal(),
            'valor_dinheiro' => $this->request->getValorDinheiro(),
            'valor_movimentado' => $this->request->getValorTotal(),
            'total_credito_loja' => null,
            'caixa_recurso_id' =>$this->request->getCaixaRecursoId(),
            'descricao' => $this->request->getDescricao(),
        ]);
    }
}
