<?php

namespace Modules\Mercado\Application;

use Modules\Mercado\UseCases\Gerenciamento\Pagamento\CriarFormaPagamento;
use Modules\Mercado\UseCases\Gerenciamento\Pagamento\EditarFormaPagamento;
use Modules\Mercado\UseCases\Gerenciamento\Pagamento\ReceberVenda;
use Modules\Mercado\UseCases\Gerenciamento\Pagamento\Requests\CriarFormaPagamentoRequest;
use Modules\Mercado\UseCases\Gerenciamento\Pagamento\Requests\EditarFormaPagamentoRequest;
use Modules\Mercado\UseCases\Gerenciamento\Pagamento\Requests\ReceberVendaRequest;

class PagamentoApplication
{
    public static function criarFormaPagamento(CriarFormaPagamentoRequest $request)
    {
        $interact = new CriarFormaPagamento($request);
        return $interact->handle();
    }

    public static function editarFormaPagamento(EditarFormaPagamentoRequest $request)
    {
        $interact = new EditarFormaPagamento($request);
        return $interact->handle();
    }

    public static function receberVenda(ReceberVendaRequest $request)
    {
        $interact = new ReceberVenda($request);
        return $interact->handle();
    }
}
