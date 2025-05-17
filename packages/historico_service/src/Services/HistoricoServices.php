<?php

namespace Jhslib\HistoricoService\Services;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Cache;

class HistoricoServices
{
    protected $connection;
    protected $connection_hist = 'historicos';
    protected static $cache_tabelas = 'hist_cache';

    /**
     * Construtor da classe.
     *
     * @param string $connection Nome do banco de dados
     */
    public function __construct(string $connection)
    {
        $this->connection = $connection;
    }

    // Função para salvar as tabelas no cache
    public function setTablesCache()
    {
        // Recupera todas as tabelas do banco de dados
        $tabelas = DB::connection($this->connection_hist)->select('SHOW TABLES');
        $cache = [];
        foreach ($tabelas as $key => $value) {
            $cache[] = $value->Tables_in_historicos;
        }

        Cache::put(self::$cache_tabelas, $cache);
    }

    public static function tableExistsInCache($tabela)
    {
        // Recupera as tabelas armazenadas no cache
        $tabelas = Cache::get(self::$cache_tabelas) ?? [];

        return in_array($tabela, $tabelas);
    }

    public function criarTabelaHistoricoPrincipal()
    {
        // Verifica se a tabela já existe antes de criá-la
        if (!Schema::connection($this->connection_hist)->hasTable('historicos')) {
            Schema::connection($this->connection_hist)->create('historicos', function (Blueprint $table) {
                $table->id(); // Chave primária
                $table->unsignedBigInteger('acao_id')->nullable(); // Referência para ações (exemplo)
                $table->unsignedBigInteger('processo_id')->nullable(); // Referência para processos
                $table->unsignedBigInteger('usuario_id')->nullable(); // Referência para usuários
                $table->text('comentario')->nullable(); // Comentários adicionais
                $table->timestamps(); // Cria os campos created_at e updated_at
            });
        }
        $this->setTablesCache();
        return $this;
    }

    /**
     * Método para criar as tabelas de histórico.
     *
     */
    public function criarAllTabelasHist()
    {
        $this->criarTabelaHistoricoPrincipal();
        $tabelas = DB::connection($this->connection)->select('SHOW TABLES');

        foreach ($tabelas as $key => $t) {
            $nome = $t->Tables_in_mercado;
            $this->criarTabelaHist($nome);
        }

        return $this;
    }

    public function criaHistoricoInicial()
    {
        DB::connection($this->connection_hist)->beginTransaction();
        $contagemCriados = 0;
        try {

            // Aumenta o limite de memória
            // ini_set('memory_limit', '12G');

            $tabelas = DB::connection($this->connection)->select('SHOW TABLES');

            // Cria o histórico principal
            $historicoPrincipalId = DB::connection($this->connection_hist)->table('historicos')->insertGetId([
                'acao_id' => null,
                'processo_id' => null,
                'usuario_id' => null,
                'comentario' => 'Histórico inicial das tabelas',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Itera sobre as tabelas do banco de dados original
            foreach ($tabelas as $t) {
                $nome = $t->Tables_in_mercado;

                // Verifica se a tabela existe no banco de dados de histórico
                if (Schema::connection($this->connection_hist)->hasTable($nome)) {
                    // Recupera os dados da tabela original em blocos de 5000, com uma cláusula orderBy
                    echo "Criando históricos: $nome\n";
                    $elementosTabelaJaCriado = DB::connection($this->connection_hist)->table($nome)->select("{$nome}_id")->get()->pluck("{$nome}_id")->toArray();

                    DB::connection($this->connection)->table($nome)->whereNotIn('id', $elementosTabelaJaCriado)
                        ->orderBy('id') // Altere 'id' para o campo de sua preferência, se necessário
                        ->chunk(50, function ($dadosTabela) use ($nome, $historicoPrincipalId, $contagemCriados) {
                            $agora = now(); // Obtém a data e hora atual
                            $dadosParaInserir = [];

                            // Prepara os dados para inserção na tabela de histórico
                            foreach ($dadosTabela as $registro) {
                                $dadosHistorico = (array) $registro;

                                // Adiciona o nome da tabela como chave e o id original
                                $dadosHistorico["{$nome}_id"] = $dadosHistorico['id'];
                                unset($dadosHistorico['id']); // Remove o campo id para evitar duplicação

                                // Adiciona o histórico principal e o banco
                                $dadosHistorico["historico_id"] = $historicoPrincipalId;
                                $dadosHistorico["banco"] = $this->connection;
                                // Verifica se o campo existe e está nulo, caso contrário, ignora
                                if (array_key_exists('created_at', $dadosHistorico) && is_null($dadosHistorico['created_at'])) {
                                    $dadosHistorico['created_at'] = $agora;
                                }
                                if (array_key_exists('updated_at', $dadosHistorico) && is_null($dadosHistorico['updated_at'])) {
                                    $dadosHistorico['updated_at'] = $agora;
                                }

                                $dadosParaInserir[] = $dadosHistorico;
                            }

                            // Insere os dados clonados na tabela de histórico
                            DB::connection($this->connection_hist)->table($nome)->insert($dadosParaInserir);
                            $contagemCriados++;
                            // Limpa o array para o próximo chunk
                            unset($dadosParaInserir);
                        });
                    echo "Históricos criados: $nome\n";
                }
            }
            if ($contagemCriados > 0) {
                DB::connection($this->connection_hist)->commit();
                // Reabilita o log de consultas, caso necessário
            } else {
                DB::connection($this->connection_hist)->rollBack();
            }
            $this->setTablesCache();
        } catch (\Exception $e) {
            DB::connection($this->connection_hist)->rollBack();
            throw $e;
        }
    }


    public function criarTabelaHist(string $nome)
    {
        // Verifica se a tabela de histórico já existe na base "historicos"
        if (!DB::connection($this->connection_hist)->getSchemaBuilder()->hasTable($nome)) {
            // Cria a tabela histórica a partir da tabela original, sem as restrições UNIQUE
            DB::connection($this->connection_hist)->statement("
                CREATE TABLE {$nome} LIKE {$this->connection}.{$nome}
            ");

            // Remove restrições UNIQUE das colunas, exceto a coluna 'id'
            $restricoesUnique = DB::connection($this->connection_hist)->select("
                SHOW INDEX FROM {$nome} WHERE Non_unique = 0 AND Column_name != 'id'
            ");

            foreach ($restricoesUnique as $restricao) {
                // Remove a restrição UNIQUE da tabela (não afetando a coluna 'id')
                DB::connection($this->connection_hist)->statement("
                    ALTER TABLE {$nome} DROP INDEX {$restricao->Key_name}
                ");
            }

            // Obter as colunas que possuem AUTO_INCREMENT, exceto a coluna `id`
            $autoIncrementColumns = DB::connection($this->connection_hist)->select("
                SHOW COLUMNS FROM {$nome} WHERE Extra = 'auto_increment' AND Field != 'id'
            ");

            foreach ($autoIncrementColumns as $column) {
                // Remove o AUTO_INCREMENT da coluna específica
                DB::connection($this->connection_hist)->statement("
                    ALTER TABLE {$nome} MODIFY {$column->Field} {$column->Type} NOT NULL
                ");
            }

            // Adiciona as colunas historico_id, nome_tabela_id e banco
            DB::connection($this->connection_hist)->getSchemaBuilder()->table($nome, function ($table) use ($nome) {
                $table->unsignedBigInteger('historico_id')->nullable()->after('id');
                $table->unsignedBigInteger($nome . '_id')->nullable()->after('id');
                $table->string('banco')->nullable();
                $table->foreign('historico_id')->references('id')->on('historicos');
            });
        } else {
            // Verifica as colunas da tabela original
            $columnsOriginal = DB::connection($this->connection)->getSchemaBuilder()->getColumnListing($nome);

            // Verifica as colunas da tabela histórica
            $columnsHist = DB::connection($this->connection_hist)->getSchemaBuilder()->getColumnListing($nome);

            // Verifica se a tabela original possui alguma coluna que não existe na tabela histórica
            $missingColumns = array_diff($columnsOriginal, $columnsHist);

            // Se houverem colunas faltando, adiciona-as na tabela histórica
            if (!empty($missingColumns)) {
                foreach ($missingColumns as $column) {
                    // Obter o tipo da coluna original
                    $columnDetails = DB::connection($this->connection)->select("SHOW COLUMNS FROM {$nome} WHERE Field = ?", [$column]);
                    if (!empty($columnDetails)) {
                        $columnDefinition = $columnDetails[0];
                        // Adiciona a coluna faltante na tabela histórica
                        DB::connection($this->connection_hist)->statement("ALTER TABLE {$nome} ADD COLUMN {$column} {$columnDefinition->Type}");
                    }
                }
            }
        }
        $this->setTablesCache();
    }

    public function criaHistorico(int $usuario_id, int $acao_id = null, int $processo_id = null, string $comentario = null)
    {
        return DB::connection($this->connection_hist)->table('historicos')->insertGetId([
            'acao_id' => $acao_id,
            'processo_id' => $processo_id,
            'usuario_id' => $usuario_id,
            'comentario' => $comentario,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function criaHistoricoTable($id, $tabela, $usuario_id = null, $acao_id = null, $processo_id = null, $comentario = null)
    {
        // Verifica se a tabela 'historicos' existe no cache
        if (!$this->tableExistsInCache('historicos')) {
            $this->criarTabelaHistoricoPrincipal();
        }

        // Verifica se a tabela do histórico existe
        if (!$this->tableExistsInCache($tabela)) {
            $this->criarTabelaHist($tabela);
        }

        // Cria o histórico
        $historicoId = $this->criaHistorico($usuario_id, $acao_id, $processo_id, $comentario);

        // Busca o registro da tabela
        $registro = DB::connection($this->connection)->table($tabela)->where('id', $id)->first();

        if (!$registro) {
            throw new Exception("Registro não encontrado para gravar o histórico.");
        }

        try {
            // Insere o histórico na tabela
            $this->inserirHistoricoTabela($tabela, $registro, $historicoId);
        } catch (QueryException $e) {
            // Verifica se o erro é relacionado à falta de coluna
            if ($e->getCode() === '42S22' && strpos($e->getMessage(), 'Unknown column') !== false) {
                // Cria a tabela novamente
                $this->criarTabelaHist($tabela);

                // Reinsere o registro após criar a tabela
                $registro = DB::connection($this->connection)->table($tabela)->where('id', $id)->first();
                if (!$registro) {
                    throw new Exception("Registro não encontrado após recriação da tabela.");
                }

                $this->inserirHistoricoTabela($tabela, $registro, $historicoId);
            } else {
                // Relança exceções diferentes de falta de coluna
                throw $e;
            }
        }

        return true;
    }



    public function inserirHistoricoTabela($tabela, $registroOriginal, $historicoId)
    {
        $dadosHistorico = (array) $registroOriginal;

        // Adiciona o nome da tabela como chave e o id original
        $dadosHistorico["{$tabela}_id"] = $dadosHistorico['id'];
        unset($dadosHistorico['id']); // Remove o campo id para evitar duplicação

        // Adiciona o histórico principal e o banco
        $dadosHistorico["historico_id"] = $historicoId;
        $dadosHistorico["banco"] = $this->connection;

        // Verifica se o campo existe e está nulo, caso contrário, ignora
        if (array_key_exists('created_at', $dadosHistorico) && is_null($dadosHistorico['created_at'])) {
            $dadosHistorico['created_at'] = now();
        }
        if (array_key_exists('updated_at', $dadosHistorico) && is_null($dadosHistorico['updated_at'])) {
            $dadosHistorico['updated_at'] = now();
        }

        return  DB::connection($this->connection_hist)->table($tabela)->insert($dadosHistorico);
    }
}
