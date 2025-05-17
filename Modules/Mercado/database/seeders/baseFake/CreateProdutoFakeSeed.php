<?php

namespace Modules\Mercado\Database\Seeders\baseFake;

use App\Models\Empresa;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Mercado\Entities\Estoque;

class CreateProdutoFakeSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Aumentar o limite de memória apenas como fallback (evitar depender disso)
        // ini_set('memory_limit', '512M');

        DB::connection('mercado')->statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::connection('mercado')->table('produtos')->truncate();
        $this->criarProdutosFake();
        DB::connection('mercado')->statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function criarProdutosFake()
    {
        $this->create();
    }

    private function criaEstoque(array $produtos, $loja_id, $tamanhoBloco = 500)
    {
        $estoques = [];
        $quantidade_total = 100;
        $quantidade_disponivel = 100;
        $quantidade_minima = 0;
        $quantidade_maxima = 0;
        $localizacao = null;

        foreach ($produtos as $key => $produtoId) {
            $estoques[] = [
                'custo' => converterParaCentavos($key + 10.50),
                'preco' => converterParaCentavos($key + 20.99),
                'quantidade_total' => $quantidade_total,
                'quantidade_disponivel' => $quantidade_disponivel,
                'quantidade_minima' => $quantidade_minima,
                'quantidade_maxima' => $quantidade_maxima,
                'localizacao' => $localizacao,
                'produto_id' => $produtoId,
                'loja_id' => $loja_id,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Inserção em blocos menores
            if (count($estoques) >= $tamanhoBloco) {
                Estoque::insert($estoques);
                $this->command->info("Bloco de " . count($estoques) . " estoques inserido para loja_id: " . $loja_id);
                $estoques = []; // Limpar o array
                gc_collect_cycles(); // Forçar coleta de lixo
            }
        }

        // Inserir registros restantes
        if (!empty($estoques)) {
            Estoque::insert($estoques);
            $this->command->info("Restante de " . count($estoques) . " estoques inserido para loja_id: " . $loja_id);
            $estoques = [];
            gc_collect_cycles();
        }
    }

    private function create($totalProdutos = 1000, $tamanhoBloco = 100)
    {
        $faker = Factory::create('pt_BR');

        // Obtém todas as empresas e as lojas associadas
        $empresas = Empresa::with('mercadoLojas')->get();

        foreach ($empresas as $empresa) {
            $produtos = [];
            $offset = 0;

            while ($offset < $totalProdutos) {
                $produtos = []; // Resetar array para cada bloco
                $limite = min($tamanhoBloco, $totalProdutos - $offset); // Calcular quantos produtos inserir neste bloco

                // Gera produtos para o bloco atual
                for ($i = $offset; $i < $offset + $limite; $i++) {
                    $codAux = str_pad((string) ($i + 1), 6, '0', STR_PAD_LEFT);
                    $produtos[] = [
                        'nome' => $faker->words(8, true),
                        'descricao' => 'Descrição do Produto Empresa ' . $empresa->id . ' - ' . $i,
                        'cod_barras' => '123456789' . $i,
                        'cod_aux' => $codAux,
                        'data_validade' => '2024-12-31',
                        'unidade_medida_id' => 1,
                        'classificacao_produto_id' => 33,
                        'fabricante_id' => rand(1, 9),
                    ];
                }

                // Inserir o bloco de produtos
                DB::connection('mercado')->table('produtos')->insert($produtos);
                $this->command->info("Bloco de " . count($produtos) . " produtos inserido para empresa_id: " . $empresa->id);

                // Obter os IDs dos produtos inseridos neste bloco
                $idsInseridos = DB::connection('mercado')->table('produtos')
                    ->orderBy('id', 'desc')
                    ->limit($limite)
                    ->pluck('id')
                    ->toArray();

                sort($idsInseridos);

                // Criar estoques para cada loja da empresa
                foreach ($empresa->mercadoLojas as $loja) {
                    $this->criaEstoque($idsInseridos, $loja->id, $tamanhoBloco);
                }

                $offset += $limite; // Avançar para o próximo bloco
                unset($produtos); // Liberar memória
                gc_collect_cycles(); // Forçar coleta de lixo
            }

            $this->command->info("Todos os produtos da empresa {$empresa->id} foram inseridos com sucesso.");
        }
    }
}
