<?php

namespace Modules\Mercado\Application;

use Modules\Mercado\Repository\MovimentacaoEstoque\MovimentacaoEstoqueRepository;
use Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque\AtualizeMovimentacaoEstoque;
use Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque\CancelarMovimentacao;
use Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque\CriarMovimentacaoEstoque;
use Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque\FinalizarMovimentacao;
use Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque\MovimentacaoEstoqueItem;
use Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque\Requests\MovimentacaoEstoqueItemRequest;
use Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque\Requests\MovimentacaoEstoqueRequest;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class MovimentacaoEstoqueApplication
{
    public static function getTodasMovimentacoes(int $lojaId)
    {
        return MovimentacaoEstoqueRepository::getTodasMovimentacoes($lojaId);
    }

    public static function getMovimentacaoEstoquePorId(int $movimentacaoId){
        return MovimentacaoEstoqueRepository::getMovimentacaoEstoquePorId($movimentacaoId);
    }

    public static function criarMovimentacaoEstoque(MovimentacaoEstoqueRequest $request){
        $interact = new CriarMovimentacaoEstoque($request);
        return $interact->handle();
    }

    public static function atualizaMovimentacaoEstoque($id, MovimentacaoEstoqueRequest $request){
        $interact = new AtualizeMovimentacaoEstoque($id, $request);
        return $interact->handle();
    }

    public static function verificarExisteciaDeMovimentacaoEstoqueEmAberto(int $lojaId){
        return MovimentacaoEstoqueRepository::verificarExisteciaDeMovimentacaoEstoqueEmAberto($lojaId);
    }

    public static function getMovimentacoesItemPorMovimentacaoId(int $movimentacao_id){
        return MovimentacaoEstoqueRepository::getMovimentacoesItemPorMovimentacaoId($movimentacao_id);
    }

    /**
     * @param MovimentacaoEstoqueItemRequest $request,
     *  Armazenar a movimentação do usuário temporariamente
     */
    public static function movimentar(MovimentacaoEstoqueItemRequest $request){
        $interact = new MovimentacaoEstoqueItem($request);
        return $interact->handle();
    }

    public static function finalizarMovimentacao(int $movimentacaoId, CriarHistoricoRequest $criarHistoricoRequest){
        $interact = new FinalizarMovimentacao($movimentacaoId, $criarHistoricoRequest);
       return $interact->handle();
    }

    public static function deletarMovimentacaoEstoqueItem(int $movimentacao_item_id, CriarHistoricoRequest $criarHistoricoRequest){
        return MovimentacaoEstoqueRepository::deletarMovimentacaoEstoqueItem($movimentacao_item_id, $criarHistoricoRequest);
    }

    public static function cancelarMovimentacao(int $movimentacao_id, CriarHistoricoRequest $criarHistoricoRequest){
        $interact = new CancelarMovimentacao($movimentacao_id, $criarHistoricoRequest);
       return $interact->handle();
    }
}
