<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Recipient;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class RecipientService
{
    public function listForUser(int $userId): Collection
    {
        return Recipient::forUser($userId)
            ->withCount('invoices')
            ->withSum('invoices', 'total_price')
            ->orderBy('company_name')
            ->orderBy('name')
            ->get();
    }

    public function createForUser(int $userId, array $validated): Recipient
    {
        $validated['user_id'] = $userId;

        return Recipient::create($validated);
    }

    public function findForUserOrFail(int $userId, int $recipientId): Recipient
    {
        $recipient = Recipient::findOrFail($recipientId);

        if ($recipient->user_id !== $userId) {
            throw ValidationException::withMessages([
                'recipient_id' => __('messages.recipient_not_owned'),
            ]);
        }

        return $recipient;
    }

    public function update(Recipient $recipient, array $validated): Recipient
    {
        $recipient->update($validated);

        return $recipient;
    }

    public function delete(Recipient $recipient): void
    {
        $recipient->delete();
    }
}
