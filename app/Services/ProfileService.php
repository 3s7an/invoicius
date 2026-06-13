<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Currency;
use App\Models\VatType;
use App\Models\User;
use App\Models\UserCompanyLogo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    public function getEditData(): array
    {
        return [
            'currencies' => Currency::orderBy('name')->get(['id', 'name', 'symbol']),
            'vat_types' => VatType::orderBy('code')->get(['id', 'code', 'rate']),
        ];
    }

    public function updateProfile(User $user, array $data): void
    {
        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
    }

    public function updateDetails(User $user, array $data, ?UploadedFile $companyLogo = null): void
    {
        unset($data['company_logo']);

        $user->fill($data);
        $user->save();

        if ($companyLogo instanceof \Illuminate\Http\UploadedFile) {
            $this->updateCompanyLogo($user, $companyLogo);
        }
    }

    private function updateCompanyLogo(User $user, UploadedFile $companyLogo): void
    {
        $dir = 'company-logos/' . $user->id;
        $path = $companyLogo->store($dir, 'public');
        $fileName = $companyLogo->getClientOriginalName();
        $logo = $user->companyLogo;
        if ($logo) {
            Storage::disk('public')->delete($logo->link);
            $logo->update([
                'link' => $path,
                'file_name' => $fileName,
            ]);
            return;
        }
        $logo = UserCompanyLogo::create([
            'user_id' => $user->id,
            'link' => $path,
            'file_name' => $fileName,
        ]);
        $user->update(['company_logo_id' => $logo->id]);
    }

    public function deleteAccount(User $user): void
    {
        DB::transaction(function () use ($user): void {
            $user->delete();
            Auth::logout();
        });
    }
}
