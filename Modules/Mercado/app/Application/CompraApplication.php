<?php

namespace Modules\Mercado\Application;

use Modules\Mercado\Repository\Compra\CompraRepository;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\Pedido\Compra\CancelarCompra;
use Modules\Mercado\UseCases\Pedido\Compra\CriarCompra;
use Modules\Mercado\UseCases\Pedido\Compra\Requests\CriarCompraRequest;

class CompraApplication
{
    public static function criarCompra(CriarCompraRequest $request)
    {
        $interact = new CriarCompra($request);
        return $interact->handle();
    }

    public static function cancelarCompra(CriarHistoricoRequest $criarHistoricoRequest, int $compra_id)
    {
        $interact = new CancelarCompra($criarHistoricoRequest, $compra_id);
        return $interact->handle();
    }

    public static function obterComprasCompradas(int $loja_id){
        return CompraRepository::obterComprasCompradas($loja_id);
    }

    public static function obterCompraPorId($compra_id){
        return CompraRepository::getCompraById($compra_id);
    }
}
