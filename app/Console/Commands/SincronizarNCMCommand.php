<?php

namespace App\Console\Commands;

use App\Services\GtinService;
use App\Services\NCMService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Modules\Mercado\Entities\NCM;

class SincronizarNCMCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sincronizar:ncm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sincroniza ncms';

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
        $g = new GtinService();
        // $token = $g->getGtin('7897947619586');
        $token = $g->getGtin('7897947619586');

        // dd($token);
        $this->info('Conectando na api de ncms');
        // $url = 'https://portalunico.siscomex.gov.br/classif/api/publico/nomenclatura/download/json';
        $url = 'https://brasilapi.com.br/api/ncm/v1';

        // Realizando a requisição GET
        $response = Http::get($url);

        // Obtendo os dados em formato JSON
        $data = $response->json();

        $this->info('Sincronizando ao banco de dados local');
        if (count($data) > 0) {
            $this->sincronizaBanco($data);
        }

        $this->info('ncms sincronizados');
    }

    private function sincronizaBanco($data)
    {
        foreach ($data as $key => $v) {
            $codigo = $v['codigo'];
            // $ncm = NCM::where('codigo', $codigo)->get();
            $data_inicio = $v['data_inicio'];
            $data_fim = $v['data_fim'];
            $ncm = NCM::where('codigo', $codigo)->first();
            if (!$ncm) {
                $ncm = NCM::create([
                    'codigo' => $v['codigo'],
                    'descricao' => $v['descricao'],
                    'data_inicio' => $data_inicio,
                    'data_fim' => $data_fim,
                    'tipo_ato_ini' => $v['tipo_ato'],
                    'numero_ato_ini' => $v['numero_ato'],
                    'ano_ato_ini' => $v['ano_ato']
                ]);
            } else {
                $ncm->update([
                    'codigo' => $v['codigo'],
                    'descricao' => $v['descricao'],
                    'data_inicio' => $data_inicio,
                    'data_fim' => $data_fim,
                    'tipo_ato_ini' => $v['tipo_ato'],
                    'numero_ato_ini' => $v['numero_ato'],
                    'ano_ato_ini' => $v['ano_ato']
                ]);
            }
        }
    }
}
