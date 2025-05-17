<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Caixa;

use Exception;
use Modules\Mercado\Repository\Caixa\CaixaRepository;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class DeletePermissaoCaixa
{
    private CriarHistoricoRequest $criarHistoricoRequest;
    private int $caixa_permissao_id;

    public function __construct(int $caixa_permissao_id, CriarHistoricoRequest $criarHistoricoRequest)
    {
        $this->caixa_permissao_id = $caixa_permissao_id;
        $this->criarHistoricoRequest = $criarHistoricoRequest;
    }

    public function handle()
    {
        $this->validade();

        return self::deleteCaixaPermissao();
    }

    public function validade()
    {
        $caixa_permissao = CaixaRepository::getCaixaPermissaoById($this->caixa_permissao_id);
        //verifica se usuario esta operando o caixa atualmente
        if ($caixa_permissao->caixa->usuario_id == $caixa_permissao->usuario_id) {
            throw new Exception("Esse usuário está em operação no caixa atual, aguarde o fechamento do mesmo.", 1);
        }
    }

    public function deleteCaixaPermissao()
    {
        return CaixaRepository::deleteCaixaPermissao(
            $this->caixa_permissao_id,
            $this->criarHistoricoRequest
        );
    }
}
