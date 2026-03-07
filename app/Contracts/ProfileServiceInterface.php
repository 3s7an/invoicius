<?php

namespace App\Contracts;

use App\Models\User;
use Illuminate\Http\UploadedFile;

interface ProfileServiceInterface
{
    public function getEditData(): array;

    public function updateProfile(User $user, array $data): void;

    public function updateDetails(User $user, array $data, ?UploadedFile $companyLogo = null): void;

    public function deleteAccount(User $user): void;
}
