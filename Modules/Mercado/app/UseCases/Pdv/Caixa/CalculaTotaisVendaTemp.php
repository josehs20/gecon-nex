<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Modules\Mercado\Repository\PDV\CaixaPDVRepository;

class CalculaTotaisVendaTemp
{
    private int $caixa_id;
    private ?float $desconto_percentual;
    public function __construct(int $caixa_id, ?float $desconto_percentual = null)
    {
        $this->caixa_id = $caixa_id;
        $this->desconto_percentual = $desconto_percentual ?? 0.0;
    }

    public function handle()
    {
        return $this->calcular();
    }

    private function calcular()
    {
        $itens = CaixaPDVRepository::getItensCaixaTemp($this->caixa_id);
        $subTotal = $itens->sum('total');  // Já está em centavos? Se sim, mantém.

        // Calcula desconto em reais
        $descontoReais = ($subTotal / 100) * $this->desconto_percentual / 100;  // percentual sobre reais
        $descontoCentavos = (int) round($descontoReais * 100);  // converte para centavos, arredondado

        $total = $subTotal - $descontoCentavos;

        return [
            'sub_total' => $subTotal,                   // em centavos
            'total' => $total,                          // em centavos
            'desconto_percentual' => $this->desconto_percentual,
            'desconto_centavos' => $descontoCentavos,   // em centavos
        ];
    }
}
