<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class MultiDatabaseTransactions
{
    protected array $connections;

    public function __construct(array $connections = [])
    {
        $this->addConnections($connections);
    }
    /**
     * Adiciona uma conexão à lista de transações.
     *
     * @param string $connection
     * @return void
     */
    public function addConnections(array $connections = [])
    {
        $this->connections = $connections;
        return $this;
    }

    /**
     * Inicia transações em todas as conexões registradas.
     *
     * @return void
     */
    public function begin()
    {
        foreach ($this->connections as $connection) {
            DB::connection($connection)->beginTransaction();
        }
    }

    /**
     * Faz commit em todas as conexões.
     *
     * @return void
     */
    public function commit()
    {
        foreach ($this->connections as $connection) {
            if (DB::connection($connection)->transactionLevel() > 0) {
                DB::connection($connection)->commit();
            }
        }
    }

    /**
     * Faz rollback em todas as conexões.
     *
     * @return void
     */
    public function rollBack()
    {
        foreach ($this->connections as $connection) {
            if (DB::connection($connection)->transactionLevel() > 0) {
                DB::connection($connection)->rollBack();
            }
        }
    }
}
