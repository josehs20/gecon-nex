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
        foreach ($connections as $key => $connection) {
            try {
                $host = config('database.connections.' . $connection . '.host');
                $username = config('database.connections.' . $connection . '.username');
                $password = config('database.connections.' . $connection . '.password');
                $database = config('database.connections.' . $connection . '.database');
                $conn = new mysqli($host, $username, $password);
                if ($conn->connect_error) {
                    throw new Exception("Conexão não estabelecida", 1);
                }

                $this->line('dropando base ' . $database);

                $sql = 'DROP DATABASE IF EXISTS ' . $database;
                $conn->query($sql);

                $this->line('Criando banco ' . $database);
                $sql = 'CREATE DATABASE ' . $database . ' character set utf8 collate utf8_general_ci';
                $conn->query($sql);
            } catch (\Exception $e) {
                $this->line('não existe connections database configurada com o nome ' . $key);
            }
        }
    }
}
