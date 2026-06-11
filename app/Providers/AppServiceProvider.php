<?php

namespace App\Providers;

use App\Automatizations\Handlers\InvoiceAutoGenerationHandler;
use App\Automatizations\Handlers\InvoiceDueReminderHandler;
use App\Automatizations\Handlers\InvoiceReportHandler;
use App\Models\Automatization;
use App\Models\Invoice;
use App\Models\Recipient;
use App\Policies\AutomatizationPolicy;
use App\Policies\InvoicePolicy;
use App\Policies\RecipientPolicy;
use App\Services\AutomatizationProcessor;
use App\Services\AutomatizationService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AutomatizationProcessor::class, function ($app) {
            $processor = new AutomatizationProcessor();
            $processor->registerHandler($app->make(InvoiceAutoGenerationHandler::class));
            $processor->registerHandler($app->make(InvoiceReportHandler::class));
            $processor->registerHandler($app->make(InvoiceDueReminderHandler::class));

            return $processor;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Invoice::class, InvoicePolicy::class);
        Gate::policy(Recipient::class, RecipientPolicy::class);
        Gate::policy(Automatization::class, AutomatizationPolicy::class);

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Vite::prefetch(concurrency: 3);
    }
}
