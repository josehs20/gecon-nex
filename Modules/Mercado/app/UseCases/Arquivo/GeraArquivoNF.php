<?php

namespace Modules\Mercado\UseCases\Arquivo;

use NFePHP\DA\NFe\Danfe;
use NFePHP\DA\NFe\DanfeSimples;
use Spatie\ArrayToXml\ArrayToXml;


class GeraArquivoNF
{
    private array $nota;

    // Construtor para inicializar os parâmetros
    public function __construct(array $nota)
    {
        $this->nota = $nota;
    }

    public function handle()
    {
        return $this->criaDanfe($this->criaXml());
    }

    private function criaXml()
    {
        // Recuperando os dados da sua nota
        $nfe = $this->nota['nfeProc']['NFe'];

        // Garantindo que o atributo 'Id' está configurado corretamente
        if (isset($nfe['infNFe']['Id'])) {
            // Ajustando para adicionar o 'Id' como atributo
            $nfe['infNFe'] = [
                '@attributes' => [
                    'Id' => $nfe['infNFe']['Id'], // Aqui está o atributo
                    'versao' => '1.10'            // Aqui está o outro atributo, exemplo
                ]
            ] + $nfe['infNFe']; // Mesclando com os outros elementos de 'infNFe'
        }

        return ArrayToXml::convert($nfe, 'NFe', true, 'UTF-8');
    }

    private function criaDanfe($xml)
    {
        $danfe = new Danfe($xml);
        return $danfe->render();
    }
}
