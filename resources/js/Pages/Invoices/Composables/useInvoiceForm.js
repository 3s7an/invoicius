import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { createInvoiceFormDefaults, invoicePayload } from '../Utils/invoiceFormDefaults';

function firstErrorMatching(errors, prefixes) {
    const key = Object.keys(errors).find((errorKey) =>
        prefixes.some((prefix) => errorKey === prefix || errorKey.startsWith(`${prefix}.`))
    );

    return key ? errors[key] : undefined;
}

function displayErrors(errors) {
    return {
        ...errors,
        issuer_name: errors['issuer.name'] ?? errors.issuer_name,
        recipient_name: errors['recipient.recipient_name'] ?? errors.recipient_name,
        items: errors.items ?? firstErrorMatching(errors, ['items']),
    };
}

export function useInvoiceForm(props, user) {
    const isEdit = computed(() => props.mode === 'edit');
    const sourceInvoice = computed(() => props.invoice ?? {});

    const form = useForm(
        createInvoiceFormDefaults({
            mode: props.mode,
            invoice: props.invoice,
            suggestedNumber: props.suggestedNumber,
            preselectedRecipient: props.preselectedRecipient,
            currencies: props.currencies,
            defaultCurrencyId: props.defaultCurrencyId,
            vatTypes: props.vatTypes,
            user,
        })
    );

    const header = computed({
        get: () => ({
            number: form.number,
            variable_symbol: form.variable_symbol,
            issue_date: form.issue_date,
            due_date: form.due_date,
            currency_id: form.currency_id,
        }),
        set: (value) => {
            Object.assign(form, value);
        },
    });

    const currencySymbol = computed(() => {
        const currencyId = Number(form.currency_id);
        const currency = (props.currencies ?? []).find((item) => Number(item.id) === currencyId);

        return currency?.symbol ?? '';
    });

    const hasUserBillingDetails = computed(() => {
        if (!user) return false;

        return [user.street, user.street_num, user.city, user.zip, user.state, user.ico, user.dic, user.ic_dph].some(
            (value) => value != null && String(value).trim() !== ''
        );
    });

    const heading = computed(() =>
        isEdit.value
            ? `Upraviť faktúru ${sourceInvoice.value?.number ?? ''}`
            : 'Nová faktúra'
    );

    const recipientDescription = computed(() =>
        isEdit.value
            ? 'Vyhľadajte a vyberte klienta alebo upravte údaje nižšie.'
            : 'Vyhľadajte a vyberte klienta alebo pridajte nového.'
    );

    const submitLabel = computed(() => (isEdit.value ? 'Uložiť faktúru' : 'Vytvoriť faktúru'));
    const processingLabel = computed(() => (isEdit.value ? 'Ukladám...' : 'Vytváram...'));
    const errors = computed(() => displayErrors(form.errors));

    function submit() {
        const request = form.transform(invoicePayload);

        if (isEdit.value) {
            request.put(route('invoices.update', sourceInvoice.value.id), { preserveScroll: true });
            return;
        }

        request.post(route('invoices.store'), { preserveScroll: true });
    }

    return {
        form,
        header,
        errors,
        heading,
        recipientDescription,
        submitLabel,
        processingLabel,
        currencySymbol,
        hasUserBillingDetails,
        submit,
    };
}
