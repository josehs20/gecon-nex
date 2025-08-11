<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Exception;
use Modules\Mercado\Repository\Estoque\EstoqueRepository;
use Modules\Mercado\Repository\PDV\CaixaPDVRepository;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\AdicionarItemTempRequest;

class AdicionarItemTemp
{
    private AdicionarItemTempRequest $request;
    public function __construct(AdicionarItemTempRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $this->validade();
        $this->adicionaItem();
        return CaixaPDVRepository::getItensCaixaTemp($this->request->getCaixaId());
    }

    private function validade()
    {
        if ($this->request->getQuantidade() <= 0) {
            throw new Exception("Quantidade inválida", 1);
        }
    }

    private function adicionaItem()
    {
        $estoque = EstoqueRepository::getEstoqueById($this->request->getEstoqueId());
        
        return CaixaPDVRepository::criaItemTempAttrs($this->request->getCriarHistoricoRequest(), [
            'estoque_id' => $estoque->id,
            'quantidade' => $this->request->getQuantidade(),
            'caixa_id' => $this->request->getCaixaId(),
            'produto_id' => $estoque->produto_id,
            'preco' => $estoque->preco,
            'total' => $estoque->preco * $this->request->getQuantidade(),
        ]);
    }
}
