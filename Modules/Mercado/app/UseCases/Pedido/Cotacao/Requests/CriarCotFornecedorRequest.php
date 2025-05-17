<?php

namespace Modules\Mercado\UseCases\Pedido\Cotacao\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class CriarCotFornecedorRequest extends ServiceUseCase
{
    private $cotacao_id;
    private $loja_id;
    private $fornecedor_id;
    private $desconto;
    private $total;
    private $subTotal;
    private $frete;
    private $observacao;
    private $previsao_entrega;

    public function __construct(
        CriarHistoricoRequest $criarHistoricoRequest,
        $cotacao_id,
        $loja_id,
        $fornecedor_id,
        $desconto = null,
        $total = null,
        $subTotal = null,
        $frete = null,
        $observacao = null,
        $previsao_entrega = null
    ) {
        parent::__construct($criarHistoricoRequest);
        $this->cotacao_id = $cotacao_id;
        $this->loja_id = $loja_id;
        $this->fornecedor_id = $fornecedor_id;
        $this->desconto = $desconto;
        $this->total = $total;
        $this->subTotal = $subTotal;
        $this->frete = $frete;
        $this->observacao = $observacao;
        $this->previsao_entrega = $previsao_entrega;
    }

    public function getCotacaoId()
    {
        return $this->cotacao_id;
    }
    public function setCotacaoId($cotacao_id)
    {
        $this->cotacao_id = $cotacao_id;
    }

    public function getLojaId()
    {
        return $this->loja_id;
    }
    public function setLojaId($loja_id)
    {
        $this->loja_id = $loja_id;
    }

    public function getFornecedorId()
    {
        return $this->fornecedor_id;
    }
    public function setFornecedorId($fornecedor_id)
    {
        $this->fornecedor_id = $fornecedor_id;
    }

    public function getDesconto()
    {
        return $this->desconto;
    }
    public function setDesconto($desconto)
    {
        $this->desconto = $desconto;
    }

    public function getTotal()
    {
        return $this->total;
    }
    public function setTotal($total)
    {
        $this->total = $total;
    }

    public function getSubTotal()
    {
        return $this->subTotal;
    }
    public function setSubTotal($subTotal)
    {
        $this->subTotal = $subTotal;
    }

    public function getFrete()
    {
        return $this->frete;
    }
    public function setFrete($frete)
    {
        $this->frete = $frete;
    }

    public function getObservacao()
    {
        return $this->observacao;
    }
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;
    }

    public function getPrevisaoEntrega()
    {
        return $this->previsao_entrega;
    }
    public function setPrevisaoEntrega($previsao_entrega)
    {
        $this->previsao_entrega = $previsao_entrega;
    }
}
