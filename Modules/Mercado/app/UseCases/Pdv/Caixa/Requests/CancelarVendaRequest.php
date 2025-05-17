<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa\Requests;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\EditarStatusCaixaRequest;
class CancelarVendaRequest
{
    private EditarStatusCaixaRequest $editarStatusCaixaRequest;
    private string $n_venda;

    // Construtor para inicializar os campos
    public function __construct(string $n_venda, EditarStatusCaixaRequest $editarStatusCaixaRequest)
    {
        $this->editarStatusCaixaRequest = $editarStatusCaixaRequest;
        $this->n_venda = $n_venda;
    }

    // Getter para historicoRequest
    public function getNvenda(): string
    {
        return $this->n_venda;
    }

    public function getEditarStatusCaixa(): EditarStatusCaixaRequest
    {
        return $this->editarStatusCaixaRequest;
    }
}
