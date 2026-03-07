<?php

namespace App\Providers;

use App\Contracts\InvoiceServiceInterface;
use App\Contracts\ProfileServiceInterface;
use App\Contracts\RecipientServiceInterface;
use App\Models\Invoice;
use App\Models\Recipient;
use App\Policies\InvoicePolicy;
use App\Policies\RecipientPolicy;
use App\Services\InvoiceService;
use App\Services\ProfileService;
use App\Services\RecipientService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(InvoiceServiceInterface::class, InvoiceService::class);
        $this->app->bind(RecipientServiceInterface::class, RecipientService::class);
        $this->app->bind(ProfileServiceInterface::class, ProfileService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Invoice::class, InvoicePolicy::class);
        Gate::policy(Recipient::class, RecipientPolicy::class);

        Vite::prefetch(concurrency: 3);
    }
}
