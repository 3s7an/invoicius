import { defaultDueYMD, formatDate, nullIfBlank, todayYMD } from '@/Pages/Invoices/Utils/helpers';

export function defaultInvoiceHeader({ currencies } = {}) {
    const today = todayYMD();

    return {
        number: '',
        variable_symbol: '',
        issue_date: today,
        due_date: defaultDueYMD(),
        currency_id: currencies?.[0]?.id ?? '',
    };
}

export function recipientOptionLabel(recipient) {
    return (recipient?.company_name || recipient?.name || '').trim() || '-';
}

export function withRecipientLabel(recipient) {
    return {
        ...recipient,
        _label: recipientOptionLabel(recipient),
    };
}

export function defaultInvoiceItem(vatTypes = []) {
    return {
        name: '',
        quantity: 1,
        unit: 'hrs',
        unit_price: '',
        vat_type_id: vatTypes?.[0]?.id ?? null,
    };
}

export function recipientDetailsFromRecipient(recipient = {}) {
    return {
        recipient_name: (recipient.company_name || recipient.name || '').trim(),
        recipient_street: recipient.street ?? '',
        recipient_street_num: recipient.street_num ?? '',
        recipient_city: recipient.city ?? '',
        recipient_state: recipient.state ?? '',
        recipient_ico: recipient.ico ?? '',
        recipient_dic: recipient.dic ?? '',
        recipient_ic_dph: recipient.ic_dph ?? '',
        recipient_iban: recipient.iban ?? '',
    };
}

function recipientDetailsFromInvoice(invoice = {}) {
    return {
        recipient_name: invoice.recipient_name ?? '',
        recipient_street: invoice.recipient_street ?? '',
        recipient_street_num: invoice.recipient_street_num ?? '',
        recipient_city: invoice.recipient_city ?? '',
        recipient_state: invoice.recipient_state ?? '',
        recipient_ico: invoice.recipient_ico ?? '',
        recipient_dic: invoice.recipient_dic ?? '',
        recipient_ic_dph: invoice.recipient_ic_dph ?? '',
        recipient_iban: invoice.iban ?? '',
    };
}

function invoiceItemsFromInvoice(invoice, vatTypes) {
    if (!invoice?.items?.length) {
        return [defaultInvoiceItem(vatTypes)];
    }

    return invoice.items.map((item) => ({
        ...defaultInvoiceItem(vatTypes),
        name: item.name ?? '',
        quantity: item.quantity ?? 1,
        unit: item.unit ?? 'pcs',
        unit_price: item.unit_price ?? '',
        vat_type_id: item.vat_type_id ?? null,
    }));
}

export function createInvoiceFormDefaults({
    mode,
    invoice,
    suggestedNumber,
    preselectedRecipient,
    currencies,
    defaultCurrencyId,
    vatTypes,
    user,
}) {
    const isEdit = mode === 'edit';
    const sourceInvoice = invoice ?? {};
    const today = todayYMD();
    const defaultDue = defaultDueYMD();

    return {
        number: isEdit ? (sourceInvoice.number ?? '') : (suggestedNumber || today),
        variable_symbol: isEdit ? (sourceInvoice.varsym ?? '') : (suggestedNumber || today),
        issue_date: isEdit ? formatDate(sourceInvoice.issue_date) : today,
        due_date: isEdit ? formatDate(sourceInvoice.due_date) : defaultDue,
        currency_id: sourceInvoice.currency_id ?? defaultCurrencyId ?? (currencies?.[0]?.id ?? ''),
        recipient_id: preselectedRecipient?.id ?? sourceInvoice.recipient_id ?? null,
        issuer: {
            name: user?.name ?? '',
            street: user?.street ?? '',
            street_num: user?.street_num ?? '',
            city: user?.city ?? '',
            zip: user?.zip ?? '',
            state: user?.state ?? '',
            ico: user?.ico ?? '',
            dic: user?.dic ?? '',
            ic_dph: user?.ic_dph ?? '',
        },
        recipient: preselectedRecipient
            ? recipientDetailsFromRecipient(preselectedRecipient)
            : recipientDetailsFromInvoice(sourceInvoice),
        items: invoiceItemsFromInvoice(isEdit ? sourceInvoice : null, vatTypes),
    };
}

export function invoicePayload(data) {
    return {
        number: data.number,
        variable_symbol: data.variable_symbol,
        issue_date: data.issue_date,
        due_date: data.due_date,
        currency_id: data.currency_id,
        recipient_id: nullIfBlank(data.recipient_id),
        issuer: {
            ...data.issuer,
            name: nullIfBlank(data.issuer?.name),
        },
        recipient: Object.fromEntries(
            Object.entries(data.recipient ?? {}).map(([key, value]) => [key, nullIfBlank(value)])
        ),
        items: (data.items ?? []).map((item) => ({
            ...item,
            vat_type_id: nullIfBlank(item.vat_type_id),
        })),
    };
}
