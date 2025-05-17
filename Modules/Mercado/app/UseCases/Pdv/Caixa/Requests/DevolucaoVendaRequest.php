<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class DevolucaoVendaRequest extends ServiceUseCase
{
    private int $venda_id;
    private int $loja_id;
    private int $caixa_id;
    private int $caixa_evidencia_id;
    private int $usuario_id;
    private array $forma_devolucoes;
    private array $itens;
    
     // Construtor
     public function __construct(int $id, int $loja_id, int $caixa_id, int $caixa_evidencia_id,int $usuario_id, array $forma_devolucoes, array $itens, CriarHistoricoRequest $historicoRequest)
     {
        parent::__construct($historicoRequest);
         $this->venda_id = $id;
         $this->loja_id = $loja_id;
         $this->caixa_evidencia_id = $caixa_evidencia_id;
         $this->caixa_id = $caixa_id;
         $this->usuario_id = $usuario_id;
         $this->forma_devolucoes = $forma_devolucoes;
         $this->itens = $itens;
     }
 
     // Getter para venda_id
     public function getVendaId(): int
     {
         return $this->venda_id;
     }
 
     // Setter para venda_id
     public function setVendaId(int $venda_id): void
     {
         $this->venda_id = $venda_id;
     }
     
     public function getLojaId(): int
     {
         return $this->loja_id;
     }
 
     public function setLojaId(int $loja_id): void
     {
         $this->loja_id = $loja_id;
     }

     public function getCaixaId(): int
     {
         return $this->caixa_id;
     }
 
     public function setCaixaId(int $caixa_evidencia_id): void
     {
         $this->caixa_evidencia_id = $caixa_evidencia_id;
     }

     public function getCaixaEvidenciaId(): int
     {
         return $this->caixa_evidencia_id;
     }
 
      
     public function setCaixaEvidenciaId(int $caixa_evidencia_id): void
     {
         $this->caixa_evidencia_id = $caixa_evidencia_id;
     }

     public function getUsuarioId(): int
     {
         return $this->usuario_id;
     }
 
     public function setUsuarioId(int $usuario_id): void
     {
         $this->usuario_id = $usuario_id;
     }

     public function getFormasDevolucao(): array
     {
         return $this->forma_devolucoes;
     }
 
     public function setFormasDevolucao(array $forma_devolucoes): void
     {
         $this->forma_devolucoes = $forma_devolucoes;
     }
      // Getter para itens
      public function getItens(): array
      {
          return $this->itens;
      }
  
      // Setter para itens
      public function setItens(array $itens): void
      {
          $this->itens = $itens;
      }
}
