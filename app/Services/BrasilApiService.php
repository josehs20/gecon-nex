<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BrasilApiService
{
    private $urlBase;

    public function __construct()
    {
        $this->urlBase = 'https://brasilapi.com.br/api/';
    }

    public function getEmpresa($cnpj)
    {
        // 14324766000136
        $url = $this->urlBase . 'cnpj/v1/' . $cnpj;

        $response = Http::get($url);

        return (object) ['mensagem' => $response->json(), 'status' => $response->status()];
    }
}
