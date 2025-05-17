<?php

namespace Modules\Mercado\UseCases\Pedido\Cotacao;

use Exception;
use Modules\Mercado\Application\PedidoApplication;
use Modules\Mercado\Repository\Cotacao\CotacaoRepository;
use Modules\Mercado\Repository\Pedido\PedidoRepository;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;


class FinalizarCotacao
{
    private int $cotacao_id;
    private CriarHistoricoRequest $criarHistoricoRequest;

    public function __construct(int $cotacao_id, CriarHistoricoRequest $criarHistoricoRequest)
    {
        $this->cotacao_id = $cotacao_id;
        $this->criarHistoricoRequest = $criarHistoricoRequest;
    }

    public function handle()
    {
        $this->validate();
        $cotacao = $this->preparaCotacaoParaCompra();
        $this->atualizaPedido();
        return $cotacao;
    }

    private function validate()
    {
        //valida pedidos
        //regra atual é todos os itens estarem cotados e a cotação do fornecedor com data de entrega
        $cotacao = CotacaoRepository::getCotacaoById($this->cotacao_id);
        //então primeiro valida se todos os itens de cada fornecedor na cotação esta com preço unitário
        $qtdItensNaCotacao = $cotacao->cot_for_itens->count();
        $qtdItensNaCotacaoComValorUnitario = $cotacao->cot_for_itens->where('preco_unitario', '>', 0)->count();
        if ($qtdItensNaCotacao != $qtdItensNaCotacaoComValorUnitario) {
            throw new Exception("Verifique os valores de preço unitário, algum esta faltando.", 1);
        }
        //valida previsao de entrega dos fornecedores na cotação
        foreach ($cotacao->cot_fornecedores as $key => $cf) {
            if ($cf->previsao_entrega == null) {
                throw new Exception("Verifique a data de entrega do fornecedor " . $cf->fornecedor->nome, 1);
            }
        }
    }

    private function preparaCotacaoParaCompra()
    {
        $status_id = config('config.status.cotado');
        return CotacaoRepository::updateStatus($this->criarHistoricoRequest, $this->cotacao_id, $status_id);
    }

    private function atualizaPedido()
    {
        $cotacao = CotacaoRepository::getCotacaoById($this->cotacao_id);
        $pedidos = PedidoRepository::getPedidosById($cotacao->cot_for_itens->pluck('pedido_id')->unique()->toArray());

        //atualiza pedidos
        $status_id = config('config.status.cotado');
        foreach ($pedidos as $key => $p) {
            PedidoApplication::atualizaStatusPedido($this->criarHistoricoRequest, $p->id, $status_id);
        }
    }
}
