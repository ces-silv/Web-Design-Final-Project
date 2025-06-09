<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Registrar middleware de alias (como 'role')
        $middleware->alias([
            'role' => App\Http\Middleware\CheckRole::class,
        ]);

        // También puedes agregarlo a grupos específicos si lo necesitas
        $middleware->appendToGroup('web', [
            // Otros middlewares del grupo web
        ]);
        
        $middleware->appendToGroup('admin', [
            'role:admin', // Grupo específico para admins
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();