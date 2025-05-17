<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Balanco;

use Exception;
use Modules\Mercado\Application\BalancoApplication;
use Modules\Mercado\Application\EstoqueApplication;
use Modules\Mercado\Application\MovimentacaoEstoqueApplication;
use Modules\Mercado\Entities\Balanco;
use Modules\Mercado\Entities\MovimentacaoEstoque;
use Modules\Mercado\Repository\Balanco\BalancoRepository;
use Modules\Mercado\Repository\MovimentacaoEstoque\MovimentacaoEstoqueRepository;
use Modules\Mercado\UseCases\Gerenciamento\Estoque\Requests\UpdateQtdDisponivelRequest;
use Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque\Requests\MovimentacaoEstoqueRequest;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class FinalizarBalanco
{
    private int $balanco_id;
    private $observacao;
    private ServiceUseCase $service;
    public function __construct(ServiceUseCase $service, int $balanco_id, $observacao)
    {
        $this->balanco_id = $balanco_id;
        $this->service = $service;
        $this->observacao = $observacao;
    }

    public function handle()
    {
        $balanco = $this->getBalanco();
        $this->verificarSeExisteItensNoBalanco($balanco);
        $this->validaItensComQuantidadeEstoque();
        $balanco = $this->atualizarStatusBalanco();
        $this->atualizarAtivoBalancoItem($balanco);
        $this->atualizarEstoqueDosProdutos($balanco);
        $this->lancarMovimentacoes($balanco);

        return $balanco;
    }

    private function validaItensComQuantidadeEstoque()
    {
        return BalancoApplication::confereQuantidadeEstoqueItens($this->balanco_id);
    }

    private function verificarSeExisteItensNoBalanco($balanco)
    {
        $itens = $balanco->balanco_itens()->with('estoque')->get();
        if (count($itens) === 0) {
            throw new \Exception("Nenhum item foi adicionado ao balanço!", 1);
        }
    }

    private function getBalanco()
    {
        return BalancoRepository::getBalancoPorId($this->balanco_id);
    }

    private function atualizarStatusBalanco()
    {
        $balanco = Balanco::find($this->balanco_id);

        if ($balanco->status_id == config('config.status.concluido')) {
            throw new Exception("Balanço já concluído", 1);
        }
        return BalancoRepository::atualizarStatusBalanco(
            $this->balanco_id,
            config('config.status.concluido'),
            $this->service->getCriarHistoricoRequest(),
            $this->observacao
        );
    }

    private function atualizarAtivoBalancoItem($balanco)
    {
        $balanco_itens_id = $balanco->balanco_itens()->with('estoque')->get()->pluck('id')->toArray();
        $balanco_itens_com_ativo_atualizado = BalancoRepository::atualizarAtivoBalancoItem($balanco_itens_id, $this->service->getCriarHistoricoRequest());
        return $balanco_itens_com_ativo_atualizado;
    }

    private function atualizarEstoqueDosProdutos($balanco)
    {
        return array_map(function ($balanco_item) {
            //valida resultado operação
            // Arredonda as quantidades para 3 casas decimais
            $qtdEstoqueSistema = round($balanco_item->quantidade_estoque_sistema, 3);
            $qtdEstoqueReal = round($balanco_item->quantidade_estoque_real, 3);
            $qtdOperacao = $qtdEstoqueReal - $qtdEstoqueSistema;

            $estoque = $balanco_item->estoque;
            $qtdDisponivelEstoque = round($estoque->quantidade_disponivel, 3);
            $qtdTotalEstoque = round($estoque->quantidade_total, 3);
            $novaQtdDisponivelEstoque = $qtdDisponivelEstoque + $qtdOperacao;
            $novaQtdTotalEstoque = $qtdTotalEstoque + $qtdOperacao;

            return EstoqueApplication::updateQtdDisponivel(
                new UpdateQtdDisponivelRequest(
                    $balanco_item->estoque_id,
                    $novaQtdDisponivelEstoque,
                    $novaQtdTotalEstoque,
                    $this->service->getCriarHistoricoRequest()
                )
            );
        }, $balanco->balanco_itens()->with('estoque')->get()->all());
    }

    private function lancarMovimentacoes($balanco)
    {
        return $this->criarMovimentacaoEstoqueItens($balanco);
    }

    private function criarMovimentacaoEstoque($balanco): MovimentacaoEstoque
    {
        $loja_id = $balanco->loja_id;
        return MovimentacaoEstoqueApplication::criarMovimentacaoEstoque(
            new MovimentacaoEstoqueRequest(
                $loja_id,
                config('config.status.concluido'),
                $balanco->usuario_id,
                config('config.tipo_movimentacao_estoque.balanco'),
                $this->service->getCriarHistoricoRequest()
            )
        );
    }

    private function criarMovimentacaoEstoqueItens($balanco)
    {
        $movimentacao = $this->criarMovimentacaoEstoque($balanco);
        return array_map(function ($balanco_item) use ($movimentacao) {
            MovimentacaoEstoqueRepository::createMovimentacaoEstoqueItem(
                $balanco_item->estoque_id,
                $movimentacao->id,
                config('config.tipo_movimentacao_estoque.balanco'),
                $balanco_item->quantidade_resultado_operacional,
                1,
                $this->service->getCriarHistoricoRequest()
            );
        }, $balanco->balanco_itens()->with('estoque')->get()->all());
    }
}
