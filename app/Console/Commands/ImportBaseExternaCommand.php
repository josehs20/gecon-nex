<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Mercado\Entities\Gtin;

class ImportBaseExternaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:base';

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

        $this->info('Iniciando importação...');
        //importa json
        self::importGtinJson();
        $this->info('Importação finalizada.');
        return;
        //monta json
        $dados = [];
        Gtin::chunk(3000, function ($itens) use (&$dados) {
            foreach ($itens as $item) {
                $dados[] = $item;
            }
        });

        file_put_contents(storage_path('app/import/gtin.json'), json_encode($dados, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $this->info('Importação concluída e salva em JSON.');
        return;
        //importar base crua
        $diretorios = Storage::disk('local')->directories('import/CLOUD');

        foreach ($diretorios as $diretorio) {
            // Obtém arquivos em lotes de 100
            $arquivos = collect(Storage::disk('local')->files($diretorio))->chunk(50);

            foreach ($arquivos as $lote) {
                foreach ($lote as $arquivo) {
                    try {
                        $this->importGtin($arquivo);
                        $this->info("Arquivo processado: {$arquivo}");
                    } catch (\Exception $e) {
                        $this->error("Erro ao processar {$arquivo}: " . $e->getMessage());
                    }
                }
            }
        }

        $this->info('Importação concluída!');
        return 0;
    }

    private function valida_api_externa()
    {

    }

    private function importGtinJson()
    {
        $dados = json_decode(file_get_contents('gtin_verificado.json'), true);
        foreach ($dados as $key => $value) {
            if (!Gtin::find($value['id'])) {

                Gtin::create([
                    'gtin' => $value['gtin'],
                    'descricao' => $value['descricao'],
                    'tipo' => $value['tipo'],
                    'quantidade' => $value['quantidade'],
                    'comprimento' => $value['comprimento'],
                    'altura' => $value['altura'],
                    'largura' => $value['largura'],
                    'peso_bruto' => $value['peso_bruto'],
                    'peso_liquido' => $value['peso_liquido'],
                    'ultima_verificacao' => $value['ultima_verificacao'],
                    'ncm' => $value['ncm'],
                ]);
            }
        }
    }

    private function importGtin($arquivo)
    {
        $dados = $this->getDados($arquivo);
        if (empty($dados)) {
            return;
        }

        $dadosFormatados = $this->formatDados($dados);
        $this->salvarOuAtualizarDados($dadosFormatados);
    }

    private function getDados($caminho)
    {
        $content = Storage::disk('local')->get($caminho);
        $xml = simplexml_load_string(
            utf8_encode($content),
            "SimpleXMLElement",
            LIBXML_NOCDATA | LIBXML_COMPACT
        );

        $dados = json_decode(json_encode($xml), true);
        return $dados['ROWDATA'] ?? [];
    }

    private function formatDados($dados)
    {
        $response = [];

        foreach ($dados as $dado) {
            $row = $dado['@attributes'] ?? null;
            if ($row) {
                $gtin = $this->getDado($row, 'CODBAR');
                if ($gtin && $this->isValidBrazilianGTIN($gtin)) {
                    $response[$gtin] = [
                        'descricao' => $this->getDado($row, 'NOME'),
                        'gtin' => $gtin,
                        'ncm' => $this->getDado($row, 'NCM'),
                    ];
                }
            }
        }

        return $response;
    }

    private function getDado($dado, $chave)
    {
        $valor = $dado[$chave] ?? null;
        return $valor && trim($valor) !== '' ? trim($valor) : null;
    }

    private function isValidBrazilianGTIN($gtin)
    {
        static $cache = [];

        if (isset($cache[$gtin])) {
            return $cache[$gtin];
        }

        $length = strlen($gtin);
        $result = ctype_digit($gtin) &&
            in_array($length, [8, 12, 13, 14]) &&
            (str_starts_with($gtin, '789') || str_starts_with($gtin, '790'));

        $cache[$gtin] = $result;
        return $result;
    }

    private function salvarOuAtualizarDados($dados)
    {
        if (empty($dados)) {
            return;
        }

        $values = array_map(function ($dado) {
            return [
                'gtin' => $dado['gtin'],
                'descricao' => $dado['descricao'],
                'ncm' => $dado['ncm'],
            ];
        }, $dados);

        DB::transaction(function () use ($values) {
            Gtin::upsert(
                $values,
                ['gtin'], // Coluna única para verificar duplicatas
                ['descricao', 'ncm'] // Colunas a atualizar
            );
        });
    }
}
