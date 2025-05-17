<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Modules\Mercado\Repository\Venda\VendaRepository;

class GerarNumeroVenda
{
    private int $loja_id;
    public function __construct(int $loja_id)
    {
        $this->loja_id = $loja_id;
    }

    public function handle()
    {
        return $this->gerarNumeroVendaUnico();
    }

    // Função para gerar uma string aleatória de letras e números
    function gerarStringAleatoria($comprimento, $caracteres)
    {
        $string = '';
        for ($i = 0; $i < $comprimento; $i++) {
            $string .= $caracteres[random_int(0, strlen($caracteres) - 1)];
        }
        return $string;
    }

    private function gerar()
    {
        // Conjunto de caracteres
        $letras = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numeros = '0123456789';

        // Gerar partes da placa
        $letras1 = $this->gerarStringAleatoria(3, $letras); // 3 letras
        $digito = $this->gerarStringAleatoria(1, $numeros); // 1 dígito
        // $letra2 = $this->gerarStringAleatoria(1, $letras); // 1 letra
        $numeros2 = $this->gerarStringAleatoria(2, $numeros); // 2 dígitos

        // Formatar o número da venda
        $numeroVenda = "{$letras1}{$digito}{$numeros2}";

        return $numeroVenda;
    }

    private function gerarNumeroVendaUnico()
    {
        $numeroVenda = $this->gerar();

        // Verificar se o número de venda já existe
        while (VendaRepository::verificaSeNumeroVendaExiste($this->loja_id, $numeroVenda)) {
            $numeroVenda = $this->gerar();
        }
        
        return $numeroVenda;
    }
}
