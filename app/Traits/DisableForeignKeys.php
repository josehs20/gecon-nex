<?php
// app/Traits/DisableForeignKeys.php
namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait DisableForeignKeys
{
    public function disableForeignKeys(string $connection = null): void
    {
        try {
            $conn = DB::connection($connection);
            $driver = $conn->getDriverName();

            if ($driver === 'mysql') {
                $conn->statement('SET FOREIGN_KEY_CHECKS=0;');
            } elseif ($driver === 'sqlite') {
                $conn->statement('PRAGMA foreign_keys = OFF;');
            }
        } catch (\Throwable $e) {
            // silenciosamente ignora o erro se a conexão não suportar
        }
    }

    public function enableForeignKeys(string $connection = null): void
    {
        try {
            $conn = DB::connection($connection);
            $driver = $conn->getDriverName();

            if ($driver === 'mysql') {
                $conn->statement('SET FOREIGN_KEY_CHECKS=1;');
            } elseif ($driver === 'sqlite') {
                $conn->statement('PRAGMA foreign_keys = ON;');
            }
        } catch (\Throwable $e) {
            // silenciosamente ignora o erro se a conexão não suportar
        }
    }
}


