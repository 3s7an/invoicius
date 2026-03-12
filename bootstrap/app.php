<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $e, $request) {
            if (! $request->expectsJson() && $request->isMethod('POST')) {
                if ($e instanceof \Illuminate\Database\UniqueConstraintViolationException) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Invoice with this number already exists. Please try again.');
                }
                if ($e instanceof \App\Exceptions\DuplicateInvoiceNumberException) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', $e->getMessage());
                }
            }

            return null;
        });
    })->create();
