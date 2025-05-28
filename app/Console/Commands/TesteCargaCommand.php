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
    private $url = 'https://www.econodata.com.br/_nuxt3/ModalDesbloquearEmpresa.09fd6625.js';  // A URL que você quer testa
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ini_set('memory_limit', '12G'); // aumenta memória (cuidado)

        $url = 'https://www.botucatuautopecas.com.br/';
        $quantidade = 380; // use valor menor, ex: 100 conexões simultâneas por batch
        $batches = 1000;     // número de batches

        for ($batch = 1; $batch <= $batches; $batch++) {
            $this->info("Iniciando batch $batch de $batches");

            $multiHandle = curl_multi_init();
            $curlHandles = [];

            // Cria as conexões do batch atual
            for ($i = 0; $i < $quantidade; $i++) {
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'Referer: https://www.botucatuautopecas.com.br/',
                    'Connection: keep-alive'
                ]);
                curl_multi_add_handle($multiHandle, $ch);
                $curlHandles[$i] = $ch;
            }

            // Executa todas as requisições simultâneas
            $running = null;
            do {
                curl_multi_exec($multiHandle, $running);
                curl_multi_select($multiHandle);
            } while ($running > 0);

            // Processa cada resposta e verifica erros
            foreach ($curlHandles as $i => $ch) {
                $errorNumber = curl_errno($ch);
                if ($errorNumber === 0) {
                    $response = curl_multi_getcontent($ch);
                    $length = strlen($response);
                    if ($length > 0) {
                        $this->info("Batch $batch - Requisição [$i] OK: $length bytes recebidos");
                    } else {
                        $this->warn("Batch $batch - Requisição [$i] retornou 0 bytes");
                    }
                } else {
                    $errorMsg = curl_error($ch);
                    $this->error("Batch $batch - Requisição [$i] falhou com erro ($errorNumber): $errorMsg");
                }

                curl_multi_remove_handle($multiHandle, $ch);
                curl_close($ch);
            }

            curl_multi_close($multiHandle);

            $this->info("Batch $batch finalizado.\n");

            // sleep opcional para não sobrecarregar
            // sleep(1);
        }

        $this->info("Todos os batches finalizados.");
    }
}

