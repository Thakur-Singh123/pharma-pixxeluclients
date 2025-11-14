<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\Admin;
use App\Http\Middleware\Manager;
use App\Http\Middleware\MR;
use App\Http\Middleware\CanSaleMiddleware;
use App\Http\Middleware\Vendor;
use App\Http\Middleware\PurchaseManager;
use App\Http\Middleware\Counselor;
use App\Http\Middleware\EnsureValidAccessToken;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [
            __DIR__.'/../routes/web.php',
            __DIR__.'/../routes/admin.php',
            __DIR__.'/../routes/manager.php',
            __DIR__.'/../routes/mr.php',
            __DIR__.'/../routes/vendor.php',
            __DIR__.'/../routes/purchase-manager.php',
            __DIR__.'/../routes/counselor.php',
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
            'vendor' => Vendor::class,
            'purchase-manager' => PurchaseManager::class,
            'counselor' => Counselor::class,
            'ensure.token' => EnsureValidAccessToken::class,
        ]); 
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
