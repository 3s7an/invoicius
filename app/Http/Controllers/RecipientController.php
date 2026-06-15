<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\RecipientResource;
use App\Services\RecipientService;
use App\Http\Requests\StoreRecipientRequest;
use App\Http\Requests\UpdateRecipientRequest;
use App\Models\Recipient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RecipientController extends Controller
{
    public function __construct(
        private readonly RecipientService $recipientService,
    ) {
    }

    public function index(Request $request): Response
    {
        $recipients = $this->recipientService->listForUser($request->user()->id);

        return Inertia::render('Recipients/Index', [
            'recipients' => RecipientResource::collection($recipients),
        ]);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('Recipients/Create', [
            'from_invoice' => $request->boolean('from_invoice'),
        ]);
    }

    public function store(StoreRecipientRequest $request): RedirectResponse
    {
        $recipient = $this->recipientService->createForUser(
            $request->user()->id,
            $request->validated()
        );

        if ($request->boolean('from_invoice')) {
            return to_route('invoices.create', ['created_recipient_id' => $recipient->id])
                ->with('success', 'Odberateľ bol vytvorený.');
        }

        return to_route('recipients.index')
            ->with('success', 'Odberateľ bol vytvorený.');
    }

    public function edit(Request $request, Recipient $recipient): Response
    {
        $this->authorize('update', $recipient);

        return Inertia::render('Recipients/Index', [
            'recipients'        => RecipientResource::collection($this->recipientService->listForUser($request->user()->id)),
            'editing_recipient' => RecipientResource::make($recipient),
        ]);
    }

    public function update(UpdateRecipientRequest $request, Recipient $recipient): RedirectResponse
    {
        $this->authorize('update', $recipient);
        $this->recipientService->update($recipient, $request->validated());

        return to_route('recipients.index')
            ->with('success', 'Odberateľ bol upravený.');
    }

    public function destroy(Recipient $recipient): RedirectResponse
    {
        $this->authorize('delete', $recipient);
        $this->recipientService->delete($recipient);

        return to_route('recipients.index')
            ->with('success', 'Odberateľ bol zmazaný.');
    }
}
