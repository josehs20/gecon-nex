<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Laravel\Dusk\Browser;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Tests\DuskTestCase;
use TwoCaptcha\TwoCaptcha;

class GtinService
{
    private $urlBase;
    public function __construct()
    {
        $this->urlBase = 'https://gtin.rscsistemas.com.br/';
    }
    /**
     * Parte de autenticação na api
     */
    public function tokenGenerate()
    {
        $url = $this->urlBase . 'oauth/token';
        $login = config('services.gtin.logins.acesso_01');
        $maxAttempts = 3; // Número máximo de tentativas
        $attempts = 0; // Contador de tentativas

        // Gera as credenciais em Base64 para Basic Auth
        $credentials = base64_encode("{$login['username']}:{$login['password']}");

        while ($attempts < $maxAttempts) {
            // Faz a requisição POST com Authorization Basic
            $response = Http::withHeaders([
                'Accept' => 'application/json', // Espera JSON na resposta
                'Authorization' => "Basic {$credentials}", // Adiciona o header Authorization
                'Content-Type' => 'application/x-www-form-urlencoded', // Padrão OAuth
            ])->post($url);

            if ($response->successful()) {
                self::setBearerToken($response->json()['token']);
                return; // Sai da função se a requisição for bem-sucedida
            }

            $attempts++; // Incrementa o contador de tentativas
        }

        // Se chegou aqui, significa que todas as tentativas falharam
        throw new \Exception("Falha ao gerar token após {$maxAttempts} tentativas.");
    }

    public function getBearerToken()
    {
        // Cache::forget('gtin_token');
        $token =  Cache::get('gtin_token');

        if (!$token) {
            self::tokenGenerate();
            $token =  Cache::get('gtin_token');
        }

        return $token;
    }

    public function setBearerToken(string $token)
    {
        Cache::remember('gtin_token', 3300, function () use ($token) {
            return $token;
        });
    }

    /**
     * Parte de consulta
     */

    public function getGtin(string $codigo_barras)
    {
        // // URL do webhook gerado no n8n
        // $webhookUrl = 'http://n8n:5678/webhook-test/3758dee0-c63e-4938-b876-dcd8152b2b4e';

        // // Parâmetros que você deseja enviar ao n8n
        // $params = [
        //     'telefone' => '5528999796730',  // Substitua com a solução do captcha
        //     'gtin' => $codigo_barras,  // Outros parâmetros que você precisa enviar
        //     'site' => 'https://dfe-portal.svrs.rs.gov.br/Nfe/gtin',  // Outros parâmetros que você precisa enviar
        // ];

        // // Enviar requisição GET com parâmetros na URL
        // $response = Http::get($webhookUrl, $params);

        // // Exibir a resposta (para debug)
        // dd($response->body());


        $url = $this->urlBase . 'api/gtin/infor/' . $codigo_barras;
        $token = $this->getBearerToken(); // Recupera o token armazenado

        if (empty($token)) {
            throw new \Exception("Token de autenticação não está disponível.");
        }

        $response = Http::withHeaders([
            'Accept' => 'application/json', // Espera JSON na resposta
            'Authorization' => "Bearer {$token}", // Adiciona o header Authorization
        ])->get($url);

        return (object) ['mensagem' => $response->json(), 'status' => $response->status()];
    }

    /**
     * Simula o processo manual de enviar o GTIN e obter as informações
     */
    public function simulaProcessoManualGetGtin(string $codigo_barras)
    {
        $gtin = $codigo_barras;

        // Criar uma instância temporária do DuskTestCase para usar o Browser
        $testCase = new class extends DuskTestCase {
            public function browseWithResult(callable $callback)
            {
                return $this->browse($callback);
            }
        };

        $testCase->browseWithResult(function (Browser $browser) use ($gtin, &$result) {

            $browser->visit('https://dfe-portal.svrs.rs.gov.br/Nfe/Gtin')
                ->pause(5000)  // Aguarda o reCAPTCHA carregar
                ->type('#CodGtin', $gtin);  // Preenche o campo GTIN
            // ->waitFor('.btn-primary', 10)  // Aguarda até 10 segundos para o botão aparecer
            // ->click('.btn-primary')  // Simula o clique no botão
            // ->pause(5000) // Aguarda o processamento da requisição
            // ->assertSee('Resultado esperado');  // Verifique se a página contém um texto esperado

            dd($browser->getPageSource());
        });
    }

    public function getCaptcha()
    {
        // Faz a requisição inicial para capturar o CAPTCHA
        $response = Http::get('https://dfe-portal.svrs.rs.gov.br/Nfe/gtin');
        dd($response->body());
        return response()->json([
            'html' => $response->body() // Retorna o HTML completo para análise
        ]);
    }
}
