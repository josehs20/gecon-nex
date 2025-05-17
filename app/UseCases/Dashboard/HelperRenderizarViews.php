<?php

namespace App\UseCases\Dashboard;

class HelperRenderizarViews
{
    protected function calcularPorcentagem(float $total, float $quantidade){
        if(!$total){
            return 0;
        }
        return round(($quantidade * 100) / $total, 2);
    }

    protected function obterQuantidade($elemento){
        return count($elemento);
    }
}
