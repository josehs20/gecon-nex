<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa\Requests;

use Illuminate\Http\Request;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class FecharCaixaRequest extends ServiceUseCase
{
    private int $caixa_id;
    private string $senha;
    private ?string $observacao;
    private Request $request;

     // Construtor
     public function __construct(int $caixa_id, string $senha, string $observacao =null, CriarHistoricoRequest $historicoRequest, Request $request)
     {
        parent::__construct($historicoRequest);
         $this->caixa_id = $caixa_id;
         $this->senha = $senha;
         $this->observacao = $observacao;
         $this->request = $request;
     }
 
     // Getter e Setter para caixa_id
     public function getCaixaId(): int
     {
         return $this->caixa_id;
     }
 
     public function setCaixaId(int $caixa_id): void
     {
         $this->caixa_id = $caixa_id;
     }
 
     // Getter e Setter para senha
     public function getSenha(): string
     {
         return $this->senha;
     }
 
     public function setSenha(string $senha): void
     {
         $this->senha = $senha;
     }
 
     // Getter e Setter para observacao
     public function getObservacao(): ?string
     {
         return $this->observacao;
     }
 
     public function setObservacao(string $observacao = null): void
     {
         $this->observacao = $observacao;
     }
 

     public function getRequest(): Request
      {
          return $this->request;
      }
  
      public function setRequest(Request $request): void
      {
          $this->request = $request;
      }
}