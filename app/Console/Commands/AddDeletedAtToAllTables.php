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
        $database = config('database.connections.mercado.database');
        $tabelas = DB::connection('mercado')->select("SHOW TABLES");

        $tabelasIgnoradas = [
            'processo_tipo_usuario',
            // Adicione outras tabelas a serem ignoradas aqui
        ];

        foreach ($tabelas as $t) {
            $tabela = $t->{"Tables_in_{$database}"};

            if (in_array($tabela, $tabelasIgnoradas)) {
                $this->info("Ignorando tabela: {$tabela}");
                continue;
            }

            if (!Schema::connection('mercado')->hasColumn($tabela, 'deleted_at')) {
                Schema::connection('mercado')->table($tabela, function (Blueprint $table) use ($tabela) {
                    $table->softDeletes();
                });
                $this->info("Coluna 'deleted_at' adicionada à tabela: {$tabela}");
            } else {
                $this->warn("Tabela '{$tabela}' já possui a coluna 'deleted_at'.");
            }
        }

        $this->info('Processo concluído.');
    }

}
