<?php

namespace Modules\Mercado\UseCases\Pdv\Venda\Requests;

class AtualizaVendaItemRequest extends CriarVendaItemRequest
{
    private int $id;

    // Construtor para inicializar os campos
    public function __construct(
        int $id,
        CriarVendaItemRequest $criarItemRequest
    ) {
        $this->id = $id;
        parent::__construct(
            $criarItemRequest->getCriarHistoricoRequest(),
            $criarItemRequest->getVendaId(),
            $criarItemRequest->getCaixaId(),
            $criarItemRequest->getCaixaEvidenciaId(),
            $criarItemRequest->getLojaId(),
            $criarItemRequest->getEstoqueId(),
            $criarItemRequest->getProdutoId(),
            $criarItemRequest->getQuantidade(),
            $criarItemRequest->getPreco(),
            $criarItemRequest->getTotal(),
        );
    }

    // Getters e Setters para venda_id
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
