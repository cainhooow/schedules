<?php

use App\Http\Middleware\EnsureUserHasFlags;
use App\Http\Middleware\UserOwnsResource;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
        $middleware->alias([
            'flags' => EnsureUserHasFlags::class,
            'owns.service' => UserOwnsResource::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthorizationException $e, $request) {
            return response()->json([
                'message' => 'Você não tem permissão para acessar este recurso'
            ], 403);
        });
    })->create();
