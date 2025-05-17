<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use mysqli;

class InstalarBaseBdGeconCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'instalar:base {--zerar=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'instala a base de dados do gecon bancos dos modulos';

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
        $connections = ['gecon', 'mercado', 'historicos'];

        foreach ($connections as $connection) {
            $driver = config("database.connections.{$connection}.driver");

            if ($driver === 'mysql') {
                $this->instalarMySQL($connection);
            } elseif ($driver === 'sqlite') {
                $this->instalarSQLite($connection);
            } else {
                $this->line("Driver {$driver} não suportado para conexão {$connection}");
            }
        }
    }

    protected function instalarMySQL(string $connection)
    {
        try {
            $host = config("database.connections.{$connection}.host");
            $username = config("database.connections.{$connection}.username");
            $password = config("database.connections.{$connection}.password");
            $database = config("database.connections.{$connection}.database");

            $conn = new \mysqli($host, $username, $password);

            if ($conn->connect_error) {
                throw new \Exception("Conexão MySQL não estabelecida para {$connection}");
            }

            $this->line("Dropando base MySQL: {$database}");
            $conn->query("DROP DATABASE IF EXISTS {$database}");

            $this->line("Criando banco MySQL: {$database}");
            $conn->query("CREATE DATABASE {$database} CHARACTER SET utf8 COLLATE utf8_general_ci");
        } catch (\Exception $e) {
            $this->error("Erro MySQL em {$connection}: " . $e->getMessage());
        }
    }

    protected function instalarSQLite(string $connection)
    {
        try {
            $database = config("database.connections.{$connection}.database");
            $path = base_path($database);
          
            if (file_exists($path)) {
                $this->line("Removendo arquivo SQLite: {$database}");
                unlink($path);
            }

            // Cria arquivo vazio para o SQLite
            touch($path);
            $this->line("Arquivo SQLite criado: {$database}");
        } catch (\Exception $e) {
            $this->error("Erro SQLite em {$connection}: " . $e->getMessage());
        }
    }
}
