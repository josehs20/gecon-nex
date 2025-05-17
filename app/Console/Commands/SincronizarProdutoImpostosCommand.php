<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SincronizarProdutoImpostosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sincronizar:produto_impostos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sincroniza impostos dos produtos';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $url = 'https://apidoni.ibpt.org.br/api/v1/produtos';
        $params = [
            'token' => config('services.ibpt.api_key'),
            'cnpj' => '49368091000101',
            'codigo' => '0206.90.00',
            'uf' => 'RJ',
            'ex' => '0',
            'codigoInterno' => '10',
            'descricao' => 'teste produto',
            'unidadeMedida' => 'KG',
            'valor' => '10',
            'gtin' => '0000000000000',
        ];

        // Realizando a requisição GET
        $codigos = ['0206.90.00', '0206.90.01', '0206.90.02']; // Exemplo de códigos diferentes que você pode consultar em paralelo

        $responses = Http::pool(fn ($pool) => collect($codigos)->mapWithKeys(function ($codigo) use ($pool, $url, $params) {
            // Fazendo a requisição para cada código em paralelo
            return [
                $codigo => $pool->get($url, array_merge($params, ['codigo' => $codigo]))
            ];
        }));
        
        dd($responses);
    }
}
