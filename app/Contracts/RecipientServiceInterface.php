<?php

namespace App\Contracts;

use App\Models\Recipient;
use Illuminate\Support\Collection;

interface RecipientServiceInterface
{
    public function listForUser(int $userId): Collection;

    public function createForUser(int $userId, array $validated): Recipient;

    public function findForUserOrFail(int $userId, int $recipientId): Recipient;

    public function update(Recipient $recipient, array $validated): Recipient;

    public function delete(Recipient $recipient): void;
}
