<?php

namespace Modules\Mercado\Application;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\Pedido\Cotacao\AtualizaCotacao;
use Modules\Mercado\UseCases\Pedido\Cotacao\AtualizarCotFornecedor;
use Modules\Mercado\UseCases\Pedido\Cotacao\CancelarCotacao;
use Modules\Mercado\UseCases\Pedido\Cotacao\CriarCotacao;
use Modules\Mercado\UseCases\Pedido\Cotacao\CriarCotForItens;
use Modules\Mercado\UseCases\Pedido\Cotacao\CriarCotFornecedor;
use Modules\Mercado\UseCases\Pedido\Cotacao\FinalizarCotacao;
use Modules\Mercado\UseCases\Pedido\Cotacao\IniciarCotacao;
use Modules\Mercado\UseCases\Pedido\Cotacao\Requests\CriarCotacaoRequest;
use Modules\Mercado\UseCases\Pedido\Cotacao\Requests\CriarCotForItensRequest;
use Modules\Mercado\UseCases\Pedido\Cotacao\Requests\CriarCotFornecedorRequest;

class CotacaoApplication
{
    public static function inicicarCotacao(array $pedidosIds, array $fornecedoresIds, CriarHistoricoRequest $criarHistoricoRequest)
    {
        $interact = new IniciarCotacao($pedidosIds, $fornecedoresIds, $criarHistoricoRequest);
        return $interact->handle();
    }

    public static function finalizarCotacao(int $cotacao_id, CriarHistoricoRequest $criarHistoricoRequest)
    {
        $interact = new FinalizarCotacao($cotacao_id, $criarHistoricoRequest);
        return $interact->handle();
    }

    public static function atualizarCotacao(array $modelComRelacoesEmArray, CriarHistoricoRequest $criarHistoricoRequest)
    {
        $interact = new AtualizaCotacao($modelComRelacoesEmArray, $criarHistoricoRequest);
        return $interact->handle();
    }

    public static function criarCotacao(CriarCotacaoRequest $request)
    {
        $interact = new CriarCotacao($request);
        return $interact->handle();
    }

    public static function criarCotFornecedor(CriarCotFornecedorRequest $request)
    {
        $interact = new CriarCotFornecedor($request);
        return $interact->handle();
    }

    public static function atualizaCotFornecedor($id, CriarCotFornecedorRequest $request)
    {
        $interact = new AtualizarCotFornecedor($id, $request);
        return $interact->handle();
    }

    public static function criarCotForItens(CriarCotForItensRequest $request)
    {
        $interact = new CriarCotForItens($request);
        return $interact->handle();
    }

    public static function cancelarCotacao(CriarHistoricoRequest $criarHistoricoRequest, int $cotacao_id)
    {
        $interact = new CancelarCotacao($cotacao_id, $criarHistoricoRequest);
        return $interact->handle();
    }
}
