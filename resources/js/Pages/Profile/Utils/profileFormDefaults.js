import { nullIfBlank } from '@/Pages/Invoices/Utils/helpers';

export function createInvoiceSettingsDefaults() {
    return {
        company_logo: null,
        _method: 'patch',
    };
}

export function createBillingDetailsDefaults(user) {
    return {
        street: user?.street ?? '',
        street_num: user?.street_num ?? '',
        city: user?.city ?? '',
        zip: user?.zip ?? '',
        state: user?.state ?? '',
        ico: user?.ico ?? '',
        dic: user?.dic ?? '',
        ic_dph: user?.ic_dph ?? '',
        iban: user?.iban ?? '',
        currency_id: user?.currency_id ?? '',
        default_vat_type_id: user?.default_vat_type_id ?? '',
        _method: 'patch',
    };
}

export function billingDetailsPayload(data) {
    return {
        ...data,
        street: nullIfBlank(data.street),
        street_num: nullIfBlank(data.street_num),
        city: nullIfBlank(data.city),
        zip: nullIfBlank(data.zip),
        state: nullIfBlank(data.state),
        ico: nullIfBlank(data.ico),
        dic: nullIfBlank(data.dic),
        ic_dph: nullIfBlank(data.ic_dph),
        iban: nullIfBlank(data.iban),
        currency_id: nullIfBlank(data.currency_id),
        default_vat_type_id: nullIfBlank(data.default_vat_type_id),
        _method: 'patch',
    };
}

