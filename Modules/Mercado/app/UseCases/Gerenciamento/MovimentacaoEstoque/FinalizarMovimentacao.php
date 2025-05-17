<?php

namespace Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque;

use Modules\Mercado\Application\EstoqueApplication;
use Modules\Mercado\Application\MovimentacaoEstoqueApplication;
use Modules\Mercado\Repository\MovimentacaoEstoque\MovimentacaoEstoqueRepository;
use Modules\Mercado\UseCases\Gerenciamento\Estoque\Requests\UpdateQtdDisponivelRequest;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class FinalizarMovimentacao
{
    private int $movimentacao_id;
    private CriarHistoricoRequest $criarHistoricoRequest;

    public function __construct(int $movimentacao_id, CriarHistoricoRequest $criarHistoricoRequest)
    {
        $this->movimentacao_id = $movimentacao_id;
        $this->criarHistoricoRequest = $criarHistoricoRequest;
    }

    public function handle()
    {
        $movimentacao = $this->validade();
        $this->atualizarAtivoMovimentacaoEstoqueItem($movimentacao);
        $this->alterar_status_movimentacao($movimentacao);
        $this->atualizar_estoque($movimentacao);
        return $movimentacao;
    }

    private function validade()
    {
        $movimentacoes_itens = $this->get_movimentacoes_estoque_item();
        if ($movimentacoes_itens->count() == 0) {
            throw new \Exception("Não existe itens a serem movimentados.", 1);
        }
        foreach ($movimentacoes_itens as $key => $movItem) {
            if ($movItem->tipo_movimentacao_estoque_id == config('config.tipo_movimentacao_estoque.saida') && $movItem->quantidade_movimentada > $movItem->estoque->quantidade_disponivel) {
                throw new \Exception("A quantidade movimentada para saida do material " . $movItem->estoque->produto->getNomeCompleto(), 1);
            }
        }

        return $this->get_movimentacao_estoque();
    }

    private function get_movimentacao_estoque()
    {
        return MovimentacaoEstoqueApplication::getMovimentacaoEstoquePorId($this->movimentacao_id);
    }

    private function alterar_status_movimentacao($movimentacao)
    {
        return MovimentacaoEstoqueRepository::atualizarStatusMovimentacaoEstoque(
            $movimentacao->id,
            config('config.status.concluido'),
            $this->criarHistoricoRequest
        );
    }

    private function atualizarAtivoMovimentacaoEstoqueItem($movimentacao)
    {
        $movimentacoes_item_ids = $movimentacao->movimentacao_estoque_itens()->get()->pluck('id')->toArray();
        $movimentacao_item_ativo_atualizado = MovimentacaoEstoqueRepository::atualizarAtivoMovimentacaoEstoqueItem($movimentacoes_item_ids, $this->criarHistoricoRequest);
        return $movimentacao_item_ativo_atualizado;
    }

    private function get_movimentacoes_estoque_item()
    {
        return MovimentacaoEstoqueApplication::getMovimentacoesItemPorMovimentacaoId($this->movimentacao_id);
    }

    private function atualizar_estoque($movimentacao)
    {
        //movimentações que vai sempre entrar no estoque, no caso adicionar no estoque
        $tipo_movimentacao_entrada = [
            config('config.tipo_movimentacao_estoque.entrada'),
            config('config.tipo_movimentacao_estoque.devolucao'),
            config('config.tipo_movimentacao_estoque.recebimento')
        ];
        return array_map(function ($movItem) use ($tipo_movimentacao_entrada) {
            $estoque = $movItem->estoque;

            if (in_array($movItem->tipo_movimentacao_estoque_id, $tipo_movimentacao_entrada)) {

                $quanidade_disponivel = ($estoque->quantidade_disponivel + $movItem->quantidade_movimentada);
                $quantidade_total = ($estoque->quantidade_total + $movItem->quantidade_movimentada);

            } else {
                $quanidade_disponivel = ($estoque->quantidade_disponivel - $movItem->quantidade_movimentada);
                $quantidade_total = ($estoque->quantidade_total - $movItem->quantidade_movimentada);
            }

            EstoqueApplication::updateQtdDisponivel(
                new UpdateQtdDisponivelRequest(
                    $estoque->id,
                    $quanidade_disponivel,
                    $quantidade_total,
                    $this->criarHistoricoRequest
                )
            );
        }, $movimentacao->movimentacao_estoque_itens()->get()->all());
    }
}
