<?php

namespace Modules\Mercado\Repository\Cotacao;

use Modules\Mercado\Entities\Cotacao;
use Modules\Mercado\Entities\Pedido;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class CotacaoRepository
{
 /**
  * Parte cotação
  */
    public static function create(
        CriarHistoricoRequest $criarHistoricoRequest,
        $loja_id,
        $status_id,
        $usuario_id,
        $data_abertura,
        $descricao = null,
        $data_encerramento = null
    ) {
        Cotacao::setHistorico($criarHistoricoRequest);
        return Cotacao::create([
            'loja_id' => $loja_id,
            'status_id' => $status_id,
            'usuario_id' => $usuario_id,
            'descricao' => $descricao,
            'data_abertura' => $data_abertura,
            'data_encerramento' => $data_encerramento
        ]);
    }

    public static function getCotacaoById(
        $id
    ) {
        return Cotacao::with([
            'status',
            'usuario.master',
            'cot_for_itens.produto.unidade_medida',
            'cot_fornecedores' => function ($query) {
                $query->with([
                    'cot_for_itens.produto.unidade_medida',
                    'cot_for_itens.produto.fabricante',
                    'fornecedor',
                ]);
            }
        ])->find($id);
    }

    public static function updateStatus(
        CriarHistoricoRequest $criarHistoricoRequest,
        $cotacao_id,
        $status_id
    ) {
        $cotacao = Cotacao::find($cotacao_id);
        Cotacao::setHistorico($criarHistoricoRequest);
        $cotacao->update([
            'status_id' => $status_id,
        ]);
        return $cotacao;
    }

    public static function getCotacoesBysatus(
        int $loja_id,
        array $status
    ) {
        return Cotacao::with([
            'status',
            'usuario.master',
            'cot_for_itens.produto.unidade_medida',
            'cot_fornecedores' => function ($query) {
                $query->with([
                    'cot_for_itens.produto.unidade_medida',
                    'cot_for_itens.produto.fabricante',
                    'fornecedor',
                ]);
            }
        ])
        ->where('loja_id', $loja_id)
        ->whereIn('status_id', $status)
        ->get();

    }

    public static function getCotacoes(
        int $loja_id
    ){
        return Cotacao::where('loja_id', $loja_id)->get();
    }

    public static function getCotacoesEmAberto(
        int $loja_id
    ){
        return Cotacao::where('loja_id', $loja_id)->where('status_id', config('config.status.aberto'))->get();
    }

    public static function getCotacoesEmCotacao(
        int $loja_id
    ){
        return Cotacao::where('loja_id', $loja_id)->where('status_id', config('config.status.em_cotacao'))->get();
    }

    public static function getCotacoesCotado(
        int $loja_id
    ){
        return Cotacao::where('loja_id', $loja_id)->where('status_id', config('config.status.cotado'))->get();
    }

    public static function getCotacoesCanceladas(
        int $loja_id
    ){
        return Cotacao::where('loja_id', $loja_id)->where('status_id', config('config.status.cancelado'))->get();
    }

    public static function getCotacoesCompradas(
        int $loja_id
    ){
        return Cotacao::where('loja_id', $loja_id)->where('status_id', config('config.status.comprado'))->get();
    }

    public static function getCotacoesSemCompras(
        int $loja_id
    ){
        return Cotacao::whereDoesntHave('compra')
        ->where('loja_id', $loja_id)
        ->get();
    }

    public static function getCotacoesComCompras(
        int $loja_id
    ){
        return Cotacao::whereHas('compra')
        ->where('loja_id', $loja_id)
        ->get();
    }
}
