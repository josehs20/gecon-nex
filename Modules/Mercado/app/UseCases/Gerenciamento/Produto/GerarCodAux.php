<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Produto;

use Modules\Mercado\Repository\Produto\ProdutoRepository;

class GerarCodAux
{
    private int $loja_id;

    public function __construct(int $loja_id)
    {
        $this->loja_id = $loja_id;
    }

    public function handle()  {
        return $this->gerarCodigoAux();
    }
    
    // Gera um código aleatório e garante que ele seja único
    private function gerarCodigoAux(): string
    {
       
    // Obtém o último código auxiliar da loja
    $ultimoCodigo = ProdutoRepository::getUltimoCodAux($this->loja_id);

    // Se não houver um último código, comece a partir de 1 (que será formatado como 000001)
    if ($ultimoCodigo === null) {
        return '000001';
    }

    // Verifica se o último código é menor que 999999
    if ($ultimoCodigo < 999999) {
        return str_pad((string) ($ultimoCodigo + 1), 6, '0', STR_PAD_LEFT); // Incrementa e formata para 6 dígitos
    }

    throw new \Exception("O código auxiliar máximo foi alcançado para a loja: {$this->loja_id}");
    }
}
