<?php

namespace App\UseCases\Dashboard\Views;

use App\UseCases\Dashboard\HelperRenderizarViews;
use Modules\Mercado\Repository\Compra\CompraRepository;

class RenderizarViewCompra extends HelperRenderizarViews
{
    private int $loja_id;

    public function __construct(int $loja_id) {
        $this->loja_id = $loja_id;
    }

    public function handle(): array
    {
        return [
            'view_compra' => true,
            'listagem_compras' => $this->getCompras(),
            'quantidade_compras_compradas' => $this->obterQuantidade($this->getComprasCompradas()),
            'quantidade_compras_canceladas' => $this->obterQuantidade($this->getComprasCanceladas()),
            'quantidade_compras' => $this->getQuantidadeTotalCompras(),
            'porcentagens' => $this->getPorcentagens(),
            'valores' => $this->getValores() 
        ];
    }

    private function getPorcentagens(){
        return [
            'compras_efetivadas' => $this->calcularPorcentagem($this->getQuantidadeTotalCompras(), $this->obterQuantidade($this->getComprasCompradas())),
            'compras_canceladas' => $this->calcularPorcentagem($this->getQuantidadeTotalCompras(), $this->obterQuantidade($this->getComprasCanceladas())),
        ];
    }

    private function getValores(){
        return [
            'compras_em_reais_por_mes' => $this->getTotalEmComprasPorMes()
        ];
    }

    private function getQuantidadeTotalCompras(){
        return $this->obterQuantidade($this->getCompras());
    }

    private function getCompras(){
        return CompraRepository::getCompras($this->loja_id);
    }

    private function getComprasCompradas(){
        return CompraRepository::getComprasCompradas($this->loja_id);
    }

    private function getComprasCanceladas(){
        return CompraRepository::getComprasCanceladas($this->loja_id);
    }

    private function getTotalEmComprasPorMes(){
        return CompraRepository::getTotalEmComprasPorMes($this->loja_id);
    }

}
