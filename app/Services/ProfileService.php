<?php

namespace App\Services;

use App\Contracts\ProfileServiceInterface;
use App\Models\Currency;
use App\Models\InvoiceColor;
use App\Models\VatType;
use App\Models\User;
use App\Models\UserCompanyLogo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileService implements ProfileServiceInterface
{
    public function getEditData(): array
    {
        return [
            'currencies' => Currency::orderBy('name')->get(['id', 'name', 'symbol']),
            'invoice_colors' => InvoiceColor::orderBy('name')->get(['id', 'name', 'hex']),
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

        if ($companyLogo === null) {
            return;
        }

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
        DB::transaction(function () use ($user) {
            $user->delete();
            Auth::logout();
        });
    }
}
