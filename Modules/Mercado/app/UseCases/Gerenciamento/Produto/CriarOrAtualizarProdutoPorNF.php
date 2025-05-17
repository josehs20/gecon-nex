<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Produto;

use Exception;
use Modules\Mercado\UseCases\Gerenciamento\Produto\Requests\CriarOrAtualizarProdutoPorNFRequest;

class CriarOrAtualizarProdutoPorNF
{
    private CriarOrAtualizarProdutoPorNFRequest $request;

    public function __construct(CriarOrAtualizarProdutoPorNFRequest $request)
    {
        $this->request = $request;
    }

    // Getters and Setters
    public function handle()
    {
      $this->validate();


      $existe = $this->getEstoqueBycProd();
    }

    private function validate() {
        $ncm = $this->request->getAttribute('NCM');
        if ($ncm == null) {
            throw new Exception('Item '. $this->request->getAttribute('xNome').' não contém NCM', 1); 
        }
    }
    private function getEstoqueBycProd() {
        
    }
    // 'nf_master_cod',
    // 'estoque_id',
    // 'produto_id',
    // 'loja_id',
    private function cahves_a_serem_inseridas() {
        return [
            'cProd',
            'xProd',
            'cEAN',
            'cEANTrib',
            'NCM',
            'CEST',
            'CFOP',
            'vProd',
            'vUnCom',
            'vUnTrib',
            'qCom',
            'qTrib',
            'uCom',
            'uTrib',
            'indTot',
        ];
    }
}
