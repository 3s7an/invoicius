<?php

namespace App\Services;

use App\Contracts\AutomatizationHandlerInterface;
use App\Contracts\AutomatizationServiceInterface;
use App\DTOs\AutomatizationResult;
use App\DTOs\CreateAutomatizationData;
use App\Models\Automatization;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class AutomatizationService implements AutomatizationServiceInterface
{
    /** @var array<string, AutomatizationHandlerInterface> */
    private array $handlers = [];

    public function registerHandler(AutomatizationHandlerInterface $handler): void
    {
        $this->handlers[$handler->type()] = $handler;
    }

    public function processDueAutomatizations(): array
    {
        $due = Automatization::dueToday()->with(['user', 'recipient'])->get();

        Log::info('Automatization processing started', ['due_count' => $due->count()]);

        $results = [];

        foreach ($due as $automatization) {
            try {
                $handler = $this->resolveHandler($automatization->type);
                $result = $handler->handle($automatization);

                if ($result->success) {
                    $this->markAsRun($automatization, $result);
                }

                Log::info('Automatization processed', [
                    'automatization_id' => $automatization->id,
                    'type' => $automatization->type,
                    'success' => $result->success,
                ]);

                $results[] = [
                    'automatization_id' => $automatization->id,
                    'type' => $automatization->type,
                    'success' => $result->success,
                    'data' => $result->data,
                    'error' => $result->error,
                    'next_trigger' => $automatization->fresh()?->date_trigger?->toDateString(),
                ];
            } catch (\Throwable $e) {
                Log::error('Automatization failed', [
                    'automatization_id' => $automatization->id,
                    'type' => $automatization->type,
                    'exception' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                $results[] = [
                    'automatization_id' => $automatization->id,
                    'type' => $automatization->type,
                    'success' => false,
                    'data' => [],
                    'error' => $e->getMessage(),
                    'next_trigger' => null,
                ];
            }
        }

        Log::info('Automatization processing finished', [
            'total' => count($results),
            'successful' => collect($results)->where('success', true)->count(),
        ]);

        return $results;
    }

    public function listForUser(int $userId): Collection
    {
        return Automatization::forUser($userId)
            ->with('recipient')
            ->orderByDesc('created_at')
            ->get();
    }

    public function store(CreateAutomatizationData $data): Automatization
    {
        return Automatization::create([
            'user_id' => $data->userId,
            'recipient_id' => $data->recipientId,
            'type' => $data->type,
            'date_trigger' => $data->dateTrigger,
            'is_active' => true,
        ]);
    }

    public function update(Automatization $automatization, array $validated): Automatization
    {
        $automatization->update($validated);

        return $automatization;
    }

    public function delete(Automatization $automatization): void
    {
        $automatization->delete();
    }

    private function resolveHandler(string $type): AutomatizationHandlerInterface
    {
        return $this->handlers[$type]
            ?? throw new \InvalidArgumentException("No handler registered for type: {$type}");
    }

    private function markAsRun(Automatization $automatization, AutomatizationResult $result): void
    {
        $automatization->update([
            'last_run_at' => now(),
            'date_trigger' => now()->addMonth(),
            'result_data' => $result->data,
        ]);
    }
}
