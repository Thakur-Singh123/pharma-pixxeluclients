<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\Admin;
use App\Http\Middleware\Manager;
use App\Http\Middleware\MR;
use App\Http\Middleware\CanSaleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [
            __DIR__.'/../routes/web.php',
            __DIR__.'/../routes/admin.php',
            __DIR__.'/../routes/manager.php',
            __DIR__.'/../routes/mr.php',
        ],
        api: [
            __DIR__.'/../routes/api/api.php',
            __DIR__.'/../routes/api/admin.php',
            __DIR__.'/../routes/api/manager.php',
            __DIR__.'/../routes/api/mr.php',
        ],
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => Admin::class,
            'manager' => Manager::class,
            'mr' => MR::class,
            'can_sales' => CanSaleMiddleware::class,
        ]); 
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
