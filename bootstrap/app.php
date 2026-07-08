<?php

use App\Exceptions\DuplicateInvoiceNumberException;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\SetLocale;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Sentry\Laravel\Integration;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withCommands([
        __DIR__.'/../app/Console/Commands',
    ])
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            SetLocale::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, $request) {
            if (! $request->expectsJson() && $request->isMethod('POST')) {
                if ($e instanceof UniqueConstraintViolationException) {
                    return back()
                        ->withInput()
                        ->with('error', __('messages.invoice_number_taken'));
                }
                if ($e instanceof DuplicateInvoiceNumberException) {
                    return back()
                        ->withInput()
                        ->with('error', $e->getMessage());
                }
            }

            return null;
        });

        Integration::handles($exceptions);
    })->create();
