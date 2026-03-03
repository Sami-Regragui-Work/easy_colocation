<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'manage.categories' => \App\Http\Middleware\CanManageCategories::class,
            'colocation.member' => \App\Http\Middleware\ColocationMember::class,
            'valid.invitation' => \App\Http\Middleware\ValidInvitation::class,
            'no.active.colocation' => \App\Http\Middleware\NoActiveColocation::class,
            'admin' => \App\Http\Middleware\EnsureAdmin::class,
            'banned' => \App\Http\Middleware\CheckBanned::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
