<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToAllTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gecon:softDelete-tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $connection = DB::connection('mercado');
        $driver = $connection->getDriverName();

        if ($driver === 'mysql') {
            $tabelas = $connection->select('SHOW TABLES');
            $database = $connection->getDatabaseName();
        } elseif ($driver === 'sqlite') {
            $tabelas = $connection->select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
            $database = null; // não é usado para SQLite
        } else {
            $this->error("Driver {$driver} não suportado para este comando.");
            return 1;
        }

        $tabelasIgnoradas = [
            'processo_tipo_usuario',
            // outras tabelas a ignorar
        ];

        foreach ($tabelas as $t) {
            // Para MySQL o nome da tabela vem na propriedade Tables_in_database
            // Para SQLite o nome da tabela vem na propriedade 'name'
            $tabela = $driver === 'mysql' ? $t->{"Tables_in_{$database}"} : $t->name;

            if (in_array($tabela, $tabelasIgnoradas)) {
                $this->info("Ignorando tabela: {$tabela}");
                continue;
            }

            if (!Schema::connection('mercado')->hasColumn($tabela, 'deleted_at')) {
                Schema::connection('mercado')->table($tabela, function (Blueprint $table) {
                    $table->softDeletes();
                });
                $this->info("Coluna 'deleted_at' adicionada à tabela: {$tabela}");
            } else {
                $this->warn("Tabela '{$tabela}' já possui a coluna 'deleted_at'.");
            }
        }

        $this->info('Processo concluído.');
        return 0;
    }
}
