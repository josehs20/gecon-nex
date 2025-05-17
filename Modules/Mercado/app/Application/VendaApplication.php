<?php

namespace Modules\Mercado\Application;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\Pdv\Venda\AtualizaStatusVenda;
use Modules\Mercado\UseCases\Pdv\Venda\AtualizaVenda;
use Modules\Mercado\UseCases\Pdv\Venda\AtualizaVendaItem;
use Modules\Mercado\UseCases\Pdv\Venda\CriarVenda;
use Modules\Mercado\UseCases\Pdv\Venda\CriarVendaItem;
use Modules\Mercado\UseCases\Pdv\Venda\CriarVendaItens;
use Modules\Mercado\UseCases\Pdv\Venda\Requests\AtualizaVendaItemRequest;
use Modules\Mercado\UseCases\Pdv\Venda\Requests\AtualizaVendaRequest;
use Modules\Mercado\UseCases\Pdv\Venda\Requests\CriarVendaRequest;
use Modules\Mercado\UseCases\Pdv\Venda\Requests\CriarVendaItemRequest;

class VendaApplication
{
    public static function criarVenda(CriarVendaRequest $request)
    {
        $interact = new CriarVenda($request);
        return $interact->handle();
    }

    public static function atualizaVenda(AtualizaVendaRequest $request)
    {
        $interact = new AtualizaVenda($request);
        return $interact->handle();
    }

    public static function criaVendaItem(CriarVendaItemRequest $request)
    {
        $interact = new CriarVendaItem($request);
        return $interact->handle();
    }
    //recebe um array de CriarVendaItemRequest
    public static function criaVendaItens(array $itens)
    {
        $interact = new CriarVendaItens($itens);
        return $interact->handle();
    }

    public static function atualizaVendaItem(AtualizaVendaItemRequest $request)
    {
        $interact = new AtualizaVendaItem($request);
        return $interact->handle();
    }

    public static function atualiza_status_venda($venda_id, $status_id, CriarHistoricoRequest $criarHistoricoRequest)
    {
        $interact = new AtualizaStatusVenda($venda_id, $status_id, $criarHistoricoRequest);
        return $interact->handle();
    }
}
