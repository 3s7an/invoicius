import { nullIfBlank } from '@/Pages/Invoices/Utils/helpers';

export function createRecipientFormDefaults({ mode, recipient, fromInvoice }) {
    const isEdit = mode === 'edit';
    const source = recipient ?? {};

    return {
        name: isEdit ? (source.name ?? '') : '',
        company_name: isEdit ? (source.company_name ?? '') : '',
        street: isEdit ? (source.street ?? '') : '',
        street_num: isEdit ? (source.street_num ?? '') : '',
        city: isEdit ? (source.city ?? '') : '',
        zip: isEdit ? (source.zip ?? '') : '',
        state: isEdit ? (source.state ?? '') : '',
        ico: isEdit ? (source.ico ?? '') : '',
        dic: isEdit ? (source.dic ?? '') : '',
        ic_dph: isEdit ? (source.ic_dph ?? '') : '',
        iban: isEdit ? (source.iban ?? '') : '',
        from_invoice: Boolean(fromInvoice),
    };
}

export function recipientPayload(data) {
    return {
        name: nullIfBlank(data.name),
        company_name: nullIfBlank(data.company_name),
        street: nullIfBlank(data.street),
        street_num: nullIfBlank(data.street_num),
        city: nullIfBlank(data.city),
        zip: nullIfBlank(data.zip),
        state: nullIfBlank(data.state),
        ico: nullIfBlank(data.ico),
        dic: nullIfBlank(data.dic),
        ic_dph: nullIfBlank(data.ic_dph),
        iban: nullIfBlank(data.iban),
        from_invoice: Boolean(data.from_invoice),
    };
}

