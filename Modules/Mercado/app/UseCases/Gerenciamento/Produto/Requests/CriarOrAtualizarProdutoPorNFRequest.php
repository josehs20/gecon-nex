<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Produto\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class CriarOrAtualizarProdutoPorNFRequest extends ServiceUseCase
{
    private int $loja_id;
    private array $detItemNota;

     // Constructor to initialize properties
    public function __construct(
        CriarHistoricoRequest $historicoRequest,
        int $loja_id,
        array $detItemNota
    ) {
        parent::__construct($historicoRequest);
        $this->loja_id = $loja_id;
        $this->detItemNota = $detItemNota;
    }

    // Getter and Setter for loja_id
    public function getLojaId(): int
    {
        return $this->loja_id;
    }

    public function setLojaId(int $loja_id): void
    {
        $this->loja_id = $loja_id;
    }

    // Getter and Setter for nota
    public function getItem(): array
    {
        return $this->detItemNota;
    }

 
    public function getAttribute($key)
    {
        return isset($this->detItemNota['prod'][$key]) ? $this->detItemNota['prod'][$key] : null;
    }
}
