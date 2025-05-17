<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Pagamento\Requests;

class EditarFormaPagamentoRequest 
{
    // Propriedades protegidas

    protected int $id;
    protected CriarFormaPagamentoRequest $request;

    // Construtor
    public function __construct($id, CriarFormaPagamentoRequest $request)
    {
        $this->request = $request;
        $this->id = $id;
    }

   // Getters e Setters

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function getCriarFormaPagamentoRequest()  {
        return $this->request;
    }

}
