<?php

namespace Modules\Mercado\Repository\Pagamento;

use Modules\Mercado\Entities\FormaPagamento;
use Modules\Mercado\Entities\Pagamento;
use Modules\Mercado\Entities\VendaPagamento;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class PagamentoRepository
{
    public static function create(
        string $descricao,
        int $ativo,
        int $especie_pagamento_id,
        int $loja_id,
        CriarHistoricoRequest $criarHistoricoRequest
    ) {
        FormaPagamento::setHistorico($criarHistoricoRequest);
        return FormaPagamento::create([
            'descricao' => $descricao,
            'ativo' => $ativo,
            'especie_pagamento_id' => $especie_pagamento_id,
            'loja_id' => $loja_id,
        ]);
        // ->setAudit($criarHistoricoRequest);
    }

    public static function update(
        int $id,
        string $descricao,
        int $ativo,
        int $especie_pagamento_id,
        int $loja_id,
        CriarHistoricoRequest $criarHistoricoRequest
    ) {
        $forma = FormaPagamento::find($id);
        FormaPagamento::setHistorico($criarHistoricoRequest);

        $forma->update([
            'descricao' => $descricao,
            'ativo' => $ativo,
            'especie_pagamento_id' => $especie_pagamento_id,
            'loja_id' => $loja_id,
        ]);
        // $forma->setAudit($criarHistoricoRequest);
        return $forma;
    }

    public static function getAllFormaPagamentos(
        ?string $busca = ''
    ) {
        return FormaPagamento::with('especie')->where('loja_id', auth()->user()->getUserModulo->loja_id)->where('descricao', 'like', "%{$busca}%")->where('ativo', 1)->get();
    }

    public static function getFormaPagamentoById(
        int $id
    ) {
        return FormaPagamento::with('especie')->find($id);
    }

    public static function getFormaPagamentoByIds(
        array $id
    ) {
        return FormaPagamento::with('especie')->whereIn('id', $id)->get();
    }

    public static function criaVendaPagamentos(
        array $data
    ) {
        return VendaPagamento::insert($data);
    }

    public static function criaVendaPagamento(
        CriarHistoricoRequest $criarHistoricoRequest,
        int $venda_id,
        int $forma_pagamento_id,
        int $especie_pagamento_id,
        int $loja_id,
        float $valor_pago,
        float $valor,
        int $status_id,
        ?int $parcela = null,
        ?float $troco = null
    ) {
        VendaPagamento::setHistorico($criarHistoricoRequest);
        return VendaPagamento::create([
            'venda_id' => $venda_id,
            'forma_pagamento_id' => $forma_pagamento_id,
            'especie_pagamento_id' => $especie_pagamento_id,
            'loja_id' => $loja_id,
            'parcela' => $parcela,
            'valor_pago' => $valor_pago,
            'valor' => $valor,
            'troco' => $troco,
            'status_id' => $status_id
        ]);
    }

    public static function atualizaValorPago(
        CriarHistoricoRequest $criarHistoricoRequest,
        int $id,
        int $valor_pago,
        int $status_id = null
    ) {
        $vPagamento = VendaPagamento::find($id);

        VendaPagamento::setHistorico($criarHistoricoRequest);
        $vPagamento->update([
            'valor_pago' => $valor_pago,
            'status_id' => $status_id ?? $vPagamento->status_id
        ]);

        return $vPagamento;
    }

    public static function getVendaPagamentoById(int $id)
    {
        return VendaPagamento::with(['venda_pagamento_devolucao', 'venda'])->find($id);
    }


    /**
     * Pagamentos
     */
    public static function criaPagamento(
        CriarHistoricoRequest $criarHistoricoRequest,
        int $loja_id,
        int $caixa_id,
        int $caixa_evidencia_id,
        int $venda_id,
        int $venda_pagamento_id,
        int $forma_pagamento_id,
        int $especie_pagamento_id,
        float $valor,
        mixed $data_pagamento,
        ?int $parcelas = null
    ) {
        Pagamento::setHistorico($criarHistoricoRequest);
        return Pagamento::create([
            'loja_id' => $loja_id,
            'caixa_id' => $caixa_id,
            'caixa_evidencia_id' => $caixa_evidencia_id,
            'venda_id' => $venda_id,
            'venda_pagamento_id' => $venda_pagamento_id,
            'forma_pagamento_id' => $forma_pagamento_id,
            'especie_pagamento_id' => $especie_pagamento_id,
            'parcelas' => $parcelas,
            'data_pagamento' => $data_pagamento,
            'valor' => $valor,
        ]);
    }
}
