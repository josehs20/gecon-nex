<?php

namespace Modules\Mercado\UseCases\Pedido\Cotacao;

use Modules\Mercado\Application\CotacaoApplication;
use Modules\Mercado\Repository\Cotacao\CotForItemRepository;
use Modules\Mercado\Repository\Cotacao\CotFornecedorRepository;
use Modules\Mercado\Repository\Pedido\PedidoRepository;
use Modules\Mercado\UseCases\Pedido\Cotacao\Requests\CriarCotForItensRequest;

class CriarCotForItens
{
    private CriarCotForItensRequest $request;

    public function __construct(CriarCotForItensRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        return $this->criar();
    }

    // Validação dos dados
    private function validade()
    {

    }

    private function criar()
    {
        $pedido = PedidoRepository::getPedidoById($this->request->getPedidoId());
        $cotFornecedor = CotFornecedorRepository::getCotFornecedorById($this->request->getCotFornecedorId());
        $cotForItens = [];
        foreach ($pedido->pedido_itens as $key => $pd) {

            $cotForItens[] = CotForItemRepository::create(
                $this->request->getCriarHistoricoRequest(),
                $cotFornecedor->id,
                $cotFornecedor->fornecedor_id,
                $pedido->id,
                $cotFornecedor->cotacao_id,
                $pd->id,
                $pd->loja_id,
                $pd->estoque_id,
                $pd->produto_id,
                $pd->status_id,
                $pd->quantidade_pedida
            );
        }
        return $cotForItens;
    }
}
