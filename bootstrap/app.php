<?php
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(web: __DIR__.'/../routes/web.php', api: __DIR__.'/../routes/api.php', commands: __DIR__.'/../routes/console.php', health: '/up')
    ->withMiddleware(function (Middleware $middleware): void {
        // میان‌افزار زبان به درخواست‌های وب افزوده می‌شود.
        $middleware->web(append: [App\Http\Middleware\SetLocale::class]);

        // alias نقش برای routeهای admin و seller ثبت می‌شود.
        $middleware->alias([
            'role' => App\Http\Middleware\EnsureRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {})
    ->create();
