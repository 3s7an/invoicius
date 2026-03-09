<?php

namespace App\Services;

use App\Contracts\RecipientServiceInterface;
use App\Models\Recipient;
use Illuminate\Support\Collection;

class RecipientService implements RecipientServiceInterface
{
    public function listForUser(int $userId): Collection
    {
        return Recipient::forUser($userId)
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
            abort(403);
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
