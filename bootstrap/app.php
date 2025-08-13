<?php

use App\Http\Middleware\CaixaMiddleware;
use App\Http\Middleware\EtapaProcessoMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'processo' => EtapaProcessoMiddleware::class,
            'caixa' => CaixaMiddleware::class,
        ]);
              // Adiciona a configuração de proxy apenas se o ambiente for de produção
            if (env('APP_ENV') != 'local') {
                $middleware->trustProxies(at: '*');
            }
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
