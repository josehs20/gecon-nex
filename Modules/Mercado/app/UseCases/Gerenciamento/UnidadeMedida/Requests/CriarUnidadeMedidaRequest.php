<?php

namespace Modules\Mercado\UseCases\Gerenciamento\UnidadeMedida\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class CriarUnidadeMedidaRequest extends ServiceUseCase
{
    private string $nome;
    private string $sigla;
    private bool $podeSerFracionado;
    private int $empresa_master_cod;

    // Constructor to initialize properties
    public function __construct(CriarHistoricoRequest $criarHistoricoRequest, string $nome, string $sigla, bool $podeSerFracionado, int $empresa_master_cod)
    {
        parent::__construct($criarHistoricoRequest);
        $this->nome = $nome;
        $this->sigla = $sigla;
        $this->podeSerFracionado = $podeSerFracionado;
        $this->empresa_master_cod = $empresa_master_cod;
    }

    // Getters and Setters for nome
    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    // Getters and Setters for sigla
    public function getSigla(): string
    {
        return $this->sigla;
    }

    public function setSigla(string $sigla): void
    {
        $this->sigla = $sigla;
    }

    // Getters and Setters for podeSerFracionado
    public function getPodeSerFracionado(): bool
    {
        return $this->podeSerFracionado;
    }

    public function setPodeSerFracionado(bool $podeSerFracionado): void
    {
        $this->podeSerFracionado = $podeSerFracionado;
    }

      public function getEmpresaId(): string
      {
          return $this->empresa_master_cod;
      }
  
      public function setEmpresaId(int $empresa_master_cod): void
      {
          $this->empresa_master_cod = $empresa_master_cod;
      }
}
