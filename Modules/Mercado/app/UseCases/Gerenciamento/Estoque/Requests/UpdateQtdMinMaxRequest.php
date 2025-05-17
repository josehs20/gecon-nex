<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Estoque\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class UpdateQtdMinMaxRequest extends ServiceUseCase
{
    private int $id;
    private float $quantidade_minima;
    private float $quantidade_maxima;
    private ?string $localizacao;

    // Construtor
    public function __construct(CriarHistoricoRequest $criarHistoricoRequest,$id, $quantidade_minima, $quantidade_maxima, $localizacao = null)
    {
        parent::__construct($criarHistoricoRequest);
        $this->id = $id;
        $this->quantidade_minima = $quantidade_minima;
        $this->quantidade_maxima = $quantidade_maxima;
        $this->localizacao = $localizacao;
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }

    public function getQuantidadeMinima()
    {
        return $this->quantidade_minima;
    }

    public function getQuantidadeMaxima()
    {
        return $this->quantidade_maxima;
    }

    public function getLocalizacao()
    {
        return $this->localizacao;
    }

    // Setters
    public function setQuantidadeMinima($quantidade_minima)
    {
        $this->quantidade_minima = $quantidade_minima;
    }

    public function setQuantidadeMaxima($quantidade_maxima)
    {
        $this->quantidade_maxima = $quantidade_maxima;
    }

    public function setLocalizacao($localizacao)
    {
        $this->localizacao = $localizacao;
    }
}
