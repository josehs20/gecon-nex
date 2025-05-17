<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Caixa;

use Exception;
use Modules\Mercado\Repository\Caixa\CaixaRepository;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class CriaOuAtualizaPermissaoCaixa
{
    private CriarHistoricoRequest $criarHistoricoRequest;
    private int $usuario_id;
    private int $caixa_id;
    private bool $superior;

    public function __construct(int $usuario_id, int $caixa_id, bool $superior, CriarHistoricoRequest $criarHistoricoRequest)
    {
        $this->usuario_id = $usuario_id;
        $this->caixa_id = $caixa_id;
        $this->superior = $superior;
        $this->criarHistoricoRequest = $criarHistoricoRequest;
    }

    public function handle()
    {
        $this->validade();
        $caixaPermissao = self::criaCaixaPermissao();
        return $caixaPermissao;
    }

    public function validade()
    {
        $caixaPermissao = CaixaRepository::getCaixaPermissaoByUsuario($this->caixa_id, $this->usuario_id);

        if ($caixaPermissao) {
            throw new Exception("Esse usuário já contém permissão para operar esse caixa.", 1);
        }
    }

    public function criaCaixaPermissao()
    {
        return CaixaRepository::criaCaixaPermissao(
            $this->caixa_id,
            $this->usuario_id,
            $this->superior,
            $this->criarHistoricoRequest
        );
    }
}
