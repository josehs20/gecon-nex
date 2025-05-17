<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Balanco;

use Modules\Mercado\Application\BalancoApplication;
use Modules\Mercado\Repository\Balanco\BalancoRepository;
use Modules\Mercado\UseCases\Gerenciamento\Balanco\Requests\BalancoItemRequest;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class ConfereQuantidadesBalancoItens
{
    private int $balanco_id;
    public function __construct(int $balanco_id)
    {
        $this->balanco_id = $balanco_id;
    }

    public function handle()
    {
        $balancoItens = $this->getBalancoItens();

        if ($balancoItens->first()->balanco->status_id == config('config.status.aberto')) {

            $balancoItens = $this->validaItens($balancoItens);
        }

        return $this->getBalancoItens();
    }

    public function getBalancoItens()
    {
        return BalancoRepository::getBalancoItensPorBalancoId($this->balanco_id);
    }

    /**
     *necessário pois pode ocorrer do balanco esta aberto com o valor de uma operção e o estoque real ser alterado no meio do caminho
     *
     */
    public function validaItens($balancoItens)
    {
        foreach ($balancoItens as $key => $b) {
            $quantidade_disponivel = $b->estoque->quantidade_disponivel;
            // valida a quantidade sistema no balanco_itens
            if ($b->quantidade_estoque_sistema != $quantidade_disponivel) {
                $this->refazOperacao($b);
            }
        }
    }

    public function refazOperacao($balancoItem)
    {
        $quantidade_estoque_sistema = round($balancoItem->estoque->quantidade_disponivel, 3);
        $quantidade_estoque_real = round($balancoItem->quantidade_estoque_real, 3);
        $quantidade_operacional = $quantidade_estoque_real - $quantidade_estoque_sistema;

        return BalancoApplication::createBalancoItem(
            new BalancoItemRequest(
                $balancoItem->estoque_id,
                $balancoItem->loja_id,
                $quantidade_estoque_sistema,
                $quantidade_estoque_real,
                $quantidade_operacional,
                $balancoItem->balanco_id,
                0,
                config('config.tipo_movimentacao_estoque.balanco'),
                new CriarHistoricoRequest(
                    config('config.processos.gerenciamento.balanco.id'),
                    config('config.acoes.atualiza_balanco_item.id'),
                    $balancoItem->balanco->usuario_id,
                    'Sincronizou quantiades de itens no balanço em aberto.'
                )
            )
        );
    }
}
