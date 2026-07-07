<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTOs\AutomatizationDTO;
use App\Enums\AutomatizationType;
use App\Services\AutomatizationService;
use App\Services\RecipientService;
use App\Http\Requests\StoreAutomatizationRequest;
use App\Http\Requests\UpdateAutomatizationRequest;
use App\Models\Automatization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AutomatizationController extends Controller
{
    public function __construct(
        private readonly AutomatizationService $automatizationService,
        private readonly RecipientService $recipientService,
    ) {
    }

    public function index(Request $request): Response
    {
        $userId = $request->user()->id;
        $automatizations = $this->automatizationService->listForUser($userId);
        $recipients = $this->recipientService->listForUser($userId);

        return Inertia::render('Automatizations/Index', [
            'automatizations'      => $automatizations,
            'recipients'           => $recipients,
            'automatization_types' => AutomatizationType::optionsForFrontend(),
        ]);
    }

    public function create(Request $request): Response
    {
        $recipients = $this->recipientService->listForUser($request->user()->id);

        return Inertia::render('Automatizations/Create', [
            'recipients' => $recipients,
            'automatization_types' => AutomatizationType::optionsForFrontend(),
        ]);
    }

    public function edit(Request $request, Automatization $automatization): Response
    {
        $this->authorize('update', $automatization);

        $userId = $request->user()->id;
        $recipients = $this->recipientService->listForUser($userId);
        $automatization->loadMissing(['recipient']);

        return Inertia::render('Automatizations/Index', [
            'automatizations'        => $this->automatizationService->listForUser($userId),
            'recipients'             => $recipients,
            'automatization_types'   => AutomatizationType::optionsForFrontend(),
            'editing_automatization' => $automatization,
        ]);
    }

    public function store(StoreAutomatizationRequest $request): RedirectResponse
    {
        $this->automatizationService->store(
            AutomatizationDTO::fromValidated($request->validated(), $request->user()->id),
        );

        return to_route('automatizations.index')
            ->with('success', __('messages.automatization_created'));
    }

    public function update(UpdateAutomatizationRequest $request, Automatization $automatization): RedirectResponse
    {
        $this->authorize('update', $automatization);

        $this->automatizationService->update(
            $automatization,
            AutomatizationDTO::fromValidated($request->validated(), $automatization->user_id),
        );

        return to_route('automatizations.index')
            ->with('success', __('messages.automatization_updated'));
    }

    public function destroy(Automatization $automatization): RedirectResponse
    {
        $this->authorize('delete', $automatization);

        $this->automatizationService->delete($automatization);

        return to_route('automatizations.index')
            ->with('success', __('messages.automatization_deleted'));
    }
}
