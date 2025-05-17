<?php

namespace App\Console\Commands;

use App\Services\GtinService;
use Illuminate\Console\Command;
use Modules\Mercado\Entities\Gtin;

class VerificaGtinEmApiExterna extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verifica:gtins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa base externa de produtos em lotes';

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
        $gtins = Gtin::orderBy('ncm', 'asc')->whereNull('ultima_verificacao')->get();

        foreach ($gtins as $key => $g) {
            $gtinService = new GtinService();
            $gtinApi = $gtinService->getGtin($g->gtin);
            if ($gtinApi->status != 404) {
                $this->updateGtin($g, $gtinApi->mensagem);
            }
            print_r($gtinApi);
            echo $g->id . "\n";
            sleep(1);
        }
        $this->info('Verificação concluída!');
        return 0;
    }

    private function updateGtin($getin, $data)
    {
        $getin->update([
            'gtin' => $data['ean'],
            'descricao' => $data['nome'],
            'tipo' => $data['unid_abr'],
            'ultima_verificacao' => now(),
            'ncm' => $data['ncm'],
            'cest' => $data['cest'],
            'link_foto' => $data['link_foto'],
        ]);
    }
}
