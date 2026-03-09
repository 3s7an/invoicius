<?php

namespace App\Http\Controllers;

use App\Contracts\RecipientServiceInterface;
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
        private readonly RecipientServiceInterface $recipientService,
    ) {
    }

    public function index(Request $request): Response
    {
        $recipients = $this->recipientService->listForUser($request->user()->id);

        return Inertia::render('Recipients/Index', [
            'recipients' => $recipients,
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
            return redirect()
                ->route('invoices.create', ['created_recipient_id' => $recipient->id])
                ->with('success', 'Recipient created.');
        }

        return redirect()
            ->route('recipients.index')
            ->with('success', 'Recipient created.');
    }

    public function show(Recipient $recipient): Response
    {
        $this->authorize('view', $recipient);
        $recipient->load('invoices');

        return Inertia::render('Recipients/Show', [
            'recipient' => $recipient,
        ]);
    }

    public function edit(Recipient $recipient): Response
    {
        $this->authorize('update', $recipient);

        return Inertia::render('Recipients/Edit', [
            'recipient' => $recipient,
        ]);
    }

    public function update(UpdateRecipientRequest $request, Recipient $recipient): RedirectResponse
    {
        $this->authorize('update', $recipient);
        $this->recipientService->update($recipient, $request->validated());

        return redirect()
            ->route('recipients.index')
            ->with('success', 'Recipient updated.');
    }

    public function destroy(Recipient $recipient): RedirectResponse
    {
        $this->authorize('delete', $recipient);
        $this->recipientService->delete($recipient);

        return redirect()
            ->route('recipients.index')
            ->with('success', 'Recipient deleted.');
    }
}
