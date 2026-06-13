import { defaultDueYMD, formatDate, nullIfBlank, todayYMD } from '@/Pages/Invoices/Utils/helpers';

export interface InvoiceItemFormData {
    name: string
    quantity: number | string
    unit: string
    unit_price: number | string
    vat_type_id: number | string | null
}

export interface InvoiceHeaderFormData {
    number: string
    variable_symbol: string
    issue_date: string
    due_date: string
    currency_id: number | string
}

export interface RecipientSectionData {
    recipient_name: string | null
    recipient_street: string | null
    recipient_street_num: string | null
    recipient_city: string | null
    recipient_state: string | null
    recipient_ico: string | null
    recipient_dic: string | null
    recipient_ic_dph: string | null
    recipient_iban: string | null
}

export function defaultInvoiceHeader({ currencies }: { currencies?: App.Http.Resources.CurrencyResource[] } = {}) {
    const today = todayYMD();

    return {
        number: '',
        variable_symbol: '',
        issue_date: today,
        due_date: defaultDueYMD(),
        currency_id: currencies?.[0]?.id ?? '',
    };
}

export function recipientOptionLabel(recipient: App.Http.Resources.RecipientResource | null | undefined): string {
    return (recipient?.company_name || recipient?.name || '').trim() || '-';
}

export function withRecipientLabel(recipient: App.Http.Resources.RecipientResource) {
    return {
        ...recipient,
        _label: recipientOptionLabel(recipient),
    };
}

export function defaultInvoiceItem(
    defaultVatTypeId: number | null | undefined,
    vatTypes: App.Http.Resources.VatTypeResource[] = []
) {
    return {
        name: '',
        quantity: 1,
        unit: 'hrs',
        unit_price: 0,
        vat_type_id: defaultVatTypeId ?? vatTypes?.[0]?.id ?? null,
    };
}

export function recipientDetailsFromRecipient(recipient: App.Http.Resources.RecipientResource | null | undefined) {
    return {
        recipient_name: ((recipient?.company_name || recipient?.name) ?? '').trim(),
        recipient_street: recipient?.street ?? '',
        recipient_street_num: recipient?.street_num ?? '',
        recipient_city: recipient?.city ?? '',
        recipient_state: recipient?.state ?? '',
        recipient_ico: recipient?.ico ?? '',
        recipient_dic: recipient?.dic ?? '',
        recipient_ic_dph: recipient?.ic_dph ?? '',
        recipient_iban: recipient?.iban ?? '',
    };
}

function recipientDetailsFromInvoice(invoice: App.Http.Resources.InvoiceResource | null | undefined) {
    return {
        recipient_name: invoice?.recipient_name ?? '',
        recipient_street: invoice?.recipient_street ?? '',
        recipient_street_num: invoice?.recipient_street_num ?? '',
        recipient_city: invoice?.recipient_city ?? '',
        recipient_state: invoice?.recipient_state ?? '',
        recipient_ico: invoice?.recipient_ico ?? '',
        recipient_dic: invoice?.recipient_dic ?? '',
        recipient_ic_dph: invoice?.recipient_ic_dph ?? '',
        recipient_iban: invoice?.iban ?? '',
    };
}

function invoiceItemsFromInvoice(
    invoice: App.Http.Resources.InvoiceResource | null | undefined,
    vatTypes: App.Http.Resources.VatTypeResource[],
    defaultVatTypeId: number | null | undefined
) {
    if (!invoice?.items?.length) {
        return [defaultInvoiceItem(defaultVatTypeId, vatTypes)];
    }

    return invoice.items.map((item) => ({
        ...defaultInvoiceItem(defaultVatTypeId, vatTypes),
        name: item.name ?? '',
        quantity: item.quantity ?? 1,
        unit: item.unit ?? 'pcs',
        unit_price: item.unit_price ?? 0,
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
}: {
    mode?: 'create' | 'edit'
    invoice?: App.Http.Resources.InvoiceResource | null
    suggestedNumber?: string
    preselectedRecipient?: App.Http.Resources.RecipientResource | null
    currencies?: App.Http.Resources.CurrencyResource[]
    defaultCurrencyId?: number | null
    vatTypes?: App.Http.Resources.VatTypeResource[]
    user?: import('@/types/inertia').AuthUser
}) {
    const isEdit = mode === 'edit';
    const sourceInvoice = invoice ?? null;
    const today = todayYMD();
    const defaultDue = defaultDueYMD();

    return {
        number: isEdit ? (sourceInvoice?.number ?? '') : (suggestedNumber || today),
        variable_symbol: isEdit ? (sourceInvoice?.variable_symbol ?? '') : (suggestedNumber || today),
        issue_date: isEdit ? formatDate(sourceInvoice?.issue_date) : today,
        due_date: isEdit ? formatDate(sourceInvoice?.due_date) : defaultDue,
        currency_id: sourceInvoice?.currency_id ?? defaultCurrencyId ?? (currencies?.[0]?.id ?? 0),
        recipient_id: preselectedRecipient?.id ?? sourceInvoice?.recipient_id ?? null,
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
        items: invoiceItemsFromInvoice(
            isEdit ? sourceInvoice : null,
            vatTypes ?? [],
            user?.default_vat_type_id ?? null
        ),
    };
}

export function invoicePayload(data: App.DTOs.Forms.InvoiceFormData) {
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
            Object.entries(data.recipient ?? {}).map(([key, value]) => [key, nullIfBlank(value as string | null)])
        ),
        items: (data.items ?? []).map((item) => ({
            ...item,
            vat_type_id: nullIfBlank(item.vat_type_id),
        })),
    };
}
