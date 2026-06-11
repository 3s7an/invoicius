<?php

namespace App\Services;

use App\Contracts\AutomatizationHandlerInterface;
use App\DTOs\AutomatizationResultDTO;
use App\Models\Automatization;
use Illuminate\Support\Facades\Log;

class AutomatizationProcessor
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
                if (! $this->ensureUserOrAppendError($automatization, $results)) {
                    continue;
                }

                if (
                    $automatization->type === 'invoice_auto_gen'
                    && ! $this->ensureRecipientOrAppendError($automatization, $results)
                ) {
                    continue;
                }

                $handler = $this->resolveHandler($automatization->type);
                $result = $handler->handle($automatization);

                if ($result->success) {
                    $this->scheduleNextRun($automatization, $result);
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

                return $results;
            }
        }

        Log::info('Automatization processing finished', [
            'total' => count($results),
            'successful' => collect($results)->where('success', true)->count(),
        ]);

        return $results;
    }

    private function resolveHandler(string $type): AutomatizationHandlerInterface
    {
        return $this->handlers[$type]
            ?? throw new \InvalidArgumentException("No handler registered for type: {$type}");
    }

    private function scheduleNextRun(Automatization $automatization, AutomatizationResultDTO $result): void
    {
        $nextTrigger = $automatization->type === 'invoice_due_reminder'
            ? now()->addDay()
            : now()->addMonth();

        $automatization->update([
            'last_run_at' => now(),
            'date_trigger' => $nextTrigger,
            'result_data' => $result->data,
        ]);
    }

    /**
     * @param array<int, array<string, mixed>> $results
     */
    private function ensureUserOrAppendError(Automatization $automatization, array &$results): bool
    {
        if ($automatization->user_id && $automatization->user) {
            return true;
        }

        $results[] = [
            'automatization_id' => $automatization->id,
            'type' => $automatization->type,
            'success' => false,
            'data' => [],
            'error' => 'No user assigned to automatization.',
            'next_trigger' => null,
        ];

        return false;
    }

    /**
     * @param array<int, array<string, mixed>> $results
     */
    private function ensureRecipientOrAppendError(Automatization $automatization, array &$results): bool
    {
        if ($automatization->recipient_id && $automatization->recipient) {
            return true;
        }
            $results[] = [
                'automatization_id' => $automatization->id,
                'type' => $automatization->type,
                'success' => false,
                'data' => [],
                'error' => 'No recipient assigned to automatization.',
                'next_trigger' => null,
            ];

            return false;
    }
}

