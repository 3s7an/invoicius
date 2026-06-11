<?php

namespace App\Services;

use App\Contracts\AutomatizationHandlerInterface;
use App\DTOs\AutomatizationResultDTO;
use App\Exceptions\Automatization\AutomatizationException;
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
            if (! $this->ensureUserOrAppendError($automatization, $results)) {
                continue;
            }

            if (
                $automatization->type === 'invoice_auto_gen'
                && ! $this->ensureRecipientOrAppendError($automatization, $results)
            ) {
                continue;
            }

            try {
                $handler = $this->resolveHandler($automatization->type);
            } catch (\InvalidArgumentException $e) {
                $this->appendFailureResult($results, $automatization, $e->getMessage());
                continue;
            }

            $result = $this->runHandler($handler, $automatization);

            if ($result->success) {
                try {
                    $this->scheduleNextRun($automatization, $result);
                } catch (\Throwable $e) {
                    Log::error('Automatization schedule failed', [
                        'automatization_id' => $automatization->id,
                        'type' => $automatization->type,
                        'exception' => $e->getMessage(),
                    ]);

                    $result = AutomatizationResultDTO::failure(
                        'Nepodarilo sa uložiť výsledok automatizácie.',
                    );
                }
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

    private function runHandler(
        AutomatizationHandlerInterface $handler,
        Automatization $automatization,
    ): AutomatizationResultDTO {
        try {
            return $handler->handle($automatization);
        } catch (AutomatizationException $e) {
            Log::warning('Automatization business failure', [
                'automatization_id' => $automatization->id,
                'type' => $automatization->type,
                'error' => $e->getMessage(),
            ]);

            return AutomatizationResultDTO::failure($e->getMessage());
        } catch (\Throwable $e) {
            Log::error('Automatization unexpected failure', [
                'automatization_id' => $automatization->id,
                'type' => $automatization->type,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return AutomatizationResultDTO::failure(
                'Interná chyba pri spracovaní automatizácie.',
            );
        }
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

        $this->appendFailureResult(
            $results,
            $automatization,
            'Automatizácia nemá priradeného používateľa.',
        );

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

        $this->appendFailureResult(
            $results,
            $automatization,
            'Automatizácia nemá priradeného odberateľa.',
        );

        return false;
    }

    /**
     * @param array<int, array<string, mixed>> $results
     */
    private function appendFailureResult(
        array &$results,
        Automatization $automatization,
        string $error,
    ): void {
        $results[] = [
            'automatization_id' => $automatization->id,
            'type' => $automatization->type,
            'success' => false,
            'data' => [],
            'error' => $error,
            'next_trigger' => null,
        ];
    }
}
