<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\UserMiddleware; // Import UserMiddleware
use App\Http\Middleware\PetugasMiddleware;
use App\Http\Middleware\DokterMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => CheckRole::class,
            'admin' => AdminMiddleware::class,
            'user' => UserMiddleware::class, // Tambahkan alias untuk user
            'petugas' => PetugasMiddleware::class,
            'dokter' => DokterMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();