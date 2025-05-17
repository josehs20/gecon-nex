<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TesteCargaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teste:carga';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Executa um teste de carga enviando múltiplas requisições a uma URL fixa.';

    /**
     * URL para onde as requisições serão enviadas.
     *
     * @var string
     */
    private $url = 'https://www.econodata.com.br/_nuxt3/ModalDesbloquearEmpresa.09fd6625.js';  // A URL que você quer testar

    /**
     * Número fixo de requisições e concorrência.
     *
     * @var int
     */
    private $totalRequests = 1000;  // Número total de requisições
    private $concurrency = 50;      // Número de requisições simultâneas

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Exibindo informações iniciais
        $this->info("Iniciando teste de carga...");
        $this->info("URL: {$this->url}");
        $this->info("Total de requisições: {$this->totalRequests}");
        $this->info("Conexões simultâneas: {$this->concurrency}");

        // Dividindo as requisições em blocos de concorrência
        $chunks = ceil($this->totalRequests / $this->concurrency);
        $startTime = microtime(true);
        $totalResponses = 0;

        // Executando as requisições em blocos de concorrência
        for ($i = 0; $i < $chunks; $i++) {
            $requests = [];

            // Criando as requisições no bloco de concorrência
            for ($j = 0; $j < $this->concurrency; $j++) {
                if (($i * $this->concurrency + $j) >= $this->totalRequests) break;

                $requests[] = Http::get($this->url);
                
            }

            // Esperando todas as requisições do bloco
            foreach ($requests as $response) {
                $totalResponses++;
                $this->info("Resposta recebida: " . $response->status());
            }

            // Exibindo progresso
            $this->info("Progresso: {$totalResponses} de {$this->totalRequests} requisições concluídas.");
        }

        // Calculando o tempo total de execução
        $duration = microtime(true) - $startTime;
        $this->info("Teste concluído em {$duration} segundos.");
    }
}

