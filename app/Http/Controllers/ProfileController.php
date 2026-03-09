<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteAccountRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UpdateInvoiceSettingsRequest;
use App\Http\Requests\UpdateProfileDetailsRequest;
use App\Contracts\ProfileServiceInterface;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function __construct(
        private readonly ProfileServiceInterface $profileService,
    ) {
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            ...$this->profileService->getEditData(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $this->profileService->updateProfile(
            $request->user(),
            $request->validated(),
        );

        return redirect()->route('profile.edit');
    }

    public function updateDetails(UpdateProfileDetailsRequest $request): RedirectResponse
    {
        $user = $request->user();
        $this->profileService->updateDetails(
            $user,
            $request->validated(),
            $request->file('company_logo'),
        );

        return redirect()->route('profile.edit');
    }

    public function updateInvoiceSettings(UpdateInvoiceSettingsRequest $request): RedirectResponse
    {
        $user = $request->user();
        $this->profileService->updateDetails(
            $user,
            $request->validated(),
            $request->file('company_logo'),
        );

        return redirect()->route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(DeleteAccountRequest $request): RedirectResponse
    {
        $user = $request->user();
        $this->profileService->deleteAccount($user);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->to('/');
    }
}
