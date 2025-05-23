<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for database operations. This is
    | the connection which will be utilized unless another connection
    | is explicitly specified when you execute a query / statement.
    |
    */

    'default' => 'gecon',

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Below are all of the database connections defined for your application.
    | An example configuration is provided for each database system which
    | is supported by Laravel. You're free to add / remove connections.
    |
    */

    'connections' => [

        'gecon' => [
            'driver' => env('DB_CONNECTION', 'mysql'),

            // Config para SQLite
            'database' => env('DB_DATABASE', database_path('sqlite/gecon.sqlite')),

            // Config para MySQL
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),

            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,

            'options' => env('DB_CONNECTION') === 'mysql' && extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'mercado' => [
            'driver' => env('DB_CONNECTION_MERCADO', 'mysql'),

            // SQLite
            'database' => env('DB_DATABASE_MERCADO', database_path('sqlite/mercado.sqlite')),

            // MySQL
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST_MERCADO', '127.0.0.1'),
            'port' => env('DB_PORT_MERCADO', '3306'),
            'username' => env('DB_USERNAME_MERCADO', 'forge'),
            'password' => env('DB_PASSWORD_MERCADO', ''),
            'unix_socket' => env('DB_SOCKET_MERCADO', ''),

            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,

            'options' => env('DB_CONNECTION_MERCADO') === 'mysql' && extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'historicos' => [
            'driver' => env('DB_CONNECTION_HISTORICOS', 'mysql'),

            // SQLite
            'database' => env('DB_DATABASE_HISTORICOS', database_path('sqlite/historicos.sqlite')),

            // MySQL
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST_HISTORICOS', '127.0.0.1'),
            'port' => env('DB_PORT_HISTORICOS', '3306'),
            'username' => env('DB_USERNAME_HISTORICOS', 'forge'),
            'password' => env('DB_PASSWORD_HISTORICOS', ''),
            'unix_socket' => env('DB_SOCKET_HISTORICOS', ''),

            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,

            'options' => env('DB_CONNECTION_HISTORICOS') === 'mysql' && extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

    ],


    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run on the database.
    |
    */

    'migrations' => [
        'table' => 'migrations',
        'update_date_on_publish' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as Memcached. You may define your connection settings here.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_') . '_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],

];
