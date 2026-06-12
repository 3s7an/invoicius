<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\ProfileService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProfileService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\CurrencySeeder::class);
        $this->seed(\Database\Seeders\VatTypeSeeder::class);

        $this->service = $this->app->make(ProfileService::class);
    }

    public function test_get_edit_data_returns_currencies_and_vat_types(): void
    {
        $data = $this->service->getEditData();

        $this->assertArrayHasKey('currencies', $data);
        $this->assertArrayHasKey('vat_types', $data);
        $this->assertNotEmpty($data['currencies']);
        $this->assertNotEmpty($data['vat_types']);
    }

    public function test_update_profile_clears_email_verified_at_when_email_changes(): void
    {
        $user = User::factory()->create();

        $this->service->updateProfile($user, [
            'name' => $user->name,
            'email' => 'new-email@example.com',
        ]);

        $this->assertNull($user->fresh()->email_verified_at);
    }

    public function test_update_profile_keeps_email_verified_at_when_unchanged(): void
    {
        $user = User::factory()->create();
        $verifiedAt = $user->email_verified_at;

        $this->service->updateProfile($user, [
            'name' => 'Updated Name',
            'email' => $user->email,
        ]);

        $this->assertNotNull($user->fresh()->email_verified_at);
        $this->assertTrue($verifiedAt->equalTo($user->fresh()->email_verified_at));
    }

    public function test_update_details_persists_fields_and_uploads_logo(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $currency = \App\Models\Currency::first();

        $this->service->updateDetails($user, [
            'street' => 'Hlavná',
            'city' => 'Bratislava',
            'iban' => 'SK3112000000198742637541',
            'currency_id' => $currency->id,
        ], UploadedFile::fake()->image('logo.png'));

        $user->refresh();

        $this->assertSame('Hlavná', $user->street);
        $this->assertSame('Bratislava', $user->city);
        $this->assertNotNull($user->companyLogo);
        Storage::disk('public')->assertExists($user->companyLogo->link);
    }

    public function test_delete_account_soft_deletes_user(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->service->deleteAccount($user);

        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }
}
