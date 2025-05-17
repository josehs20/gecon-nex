<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Modules\Mercado\Repository\Caixa\CaixaRepository;

class CalculaFechamentoCaixa
{
    private int $caixa_id;
    public function __construct(int $caixa_id)
    {
        $this->caixa_id = $caixa_id;
    }

    public function handle()
    {
        return $this->calcular();
    }

    private function calcular()
    {
        $caixa = CaixaRepository::getCaixaById($this->caixa_id);
        $ultimaAbertura = $caixa->ultima_abertura;
        $data_abertura = $ultimaAbertura->data_abertura;
        $statusVenda = [config('config.status.concluido'), config('config.status.devolucao'), config('config.status.parcelada')];
        $vendas = CaixaRepository::getVendasByDataAberturaCaixa($caixa->id, $data_abertura, $statusVenda);

        //agrupa elas por status 
        $totalDevolucoes = $vendas->sum(function ($venda) {
            return $venda->venda_itens->sum(function ($item) {
                return $item->devolucao_item ? $item->devolucao_item->total : 0;
            });
        });

        $totalEmDinheiro = $vendas->where('forma_pagemento_id', config('config.'))->sum();

        return (object) [
            'total_vendas' => $vendas->sum('total'),
            'total_devolucao' => $totalDevolucoes,
            'total_parcelamento' => 0,
        ];
    }
}
