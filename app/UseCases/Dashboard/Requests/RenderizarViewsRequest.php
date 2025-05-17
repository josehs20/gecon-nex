<?php

namespace App\UseCases\Dashboard\Requests;

class RenderizarViewsRequest
{

    private string $view;
    private int $loja_id;

    public function __construct(   
        string $view,
        int $loja_id
    ) {
        $this->setView($view);
        $this->setLojaId($loja_id);
    }

    public function getView(): string { return $this->view; }
    public function setView(string $view): void { $this->view = $view; }

    public function getLojaId(): int { return $this->loja_id; }
    public function setLojaId(int $loja_id): void { $this->loja_id = $loja_id; }

}
