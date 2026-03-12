<?php

namespace App\Http\Controllers;

use App\Contracts\AutomatizationServiceInterface;
use App\DTOs\CreateAutomatizationData;
use App\Http\Requests\StoreAutomatizationRequest;
use App\Http\Requests\UpdateAutomatizationRequest;
use App\Models\Automatization;
use Illuminate\Http\RedirectResponse;

class AutomatizationController extends Controller
{
    public function __construct(
        private readonly AutomatizationServiceInterface $automatizationService,
    ) {
    }

    public function store(StoreAutomatizationRequest $request): RedirectResponse
    {
        $this->automatizationService->store(
            CreateAutomatizationData::fromValidated($request->validated(), $request->user()->id),
        );

        return redirect()
            ->route('dashboard')
            ->with('success', 'Automatization created.');
    }

    public function update(UpdateAutomatizationRequest $request, Automatization $automatization): RedirectResponse
    {
        $this->authorize('update', $automatization);

        $this->automatizationService->update($automatization, $request->validated());

        return redirect()
            ->route('dashboard')
            ->with('success', 'Automatization updated.');
    }

    public function destroy(Automatization $automatization): RedirectResponse
    {
        $this->authorize('delete', $automatization);

        $this->automatizationService->delete($automatization);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Automatization deleted.');
    }
}
