<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cookie;

class LocaleController extends Controller
{
    public function update(string $locale): RedirectResponse
    {
        abort_unless(in_array($locale, ['sk', 'en'], true), 404);

        Cookie::queue('locale', $locale, 60 * 24 * 365);

        return back();
    }
}
