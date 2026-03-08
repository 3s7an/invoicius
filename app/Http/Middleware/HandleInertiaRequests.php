<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();
        if ($user) {
            $user->load('companyLogo');
        }

        $authUser = null;
        if ($user) {
            $authUser = $user->only([
                'id', 'name', 'company_name', 'email', 'email_verified_at',
                'street', 'street_num', 'city', 'zip', 'state', 'ico', 'dic', 'ic_dph', 'iban',
                'currency_id', 'company_logo_id', 'invoice_color_id',
            ]);
            try {
                $authUser['company_logo'] = $user->companyLogo ? ['url' => $user->companyLogo->url] : null;
            } catch (\Throwable $e) {
                $authUser['company_logo'] = null;
            }
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $authUser,
            ],
        ];
    }
}
