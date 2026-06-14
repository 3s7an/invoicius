import { nullIfBlank } from '@/Pages/Invoices/Utils/helpers';
import type { AuthUser } from '@/types/inertia';

export interface InvoiceSettingsFormData {
    company_logo: File | null
    _method: string
}

export interface BillingDetailsFormData {
    street: string
    street_num: string
    city: string
    zip: string
    state: string
    ico: string
    dic: string
    ic_dph: string
    iban: string
    currency_id: number | string
    default_vat_type_id: number | string
    _method: string
}

export function createInvoiceSettingsDefaults(): InvoiceSettingsFormData {
    return {
        company_logo: null,
        _method: 'patch',
    };
}

export function createBillingDetailsDefaults(user: AuthUser | null): BillingDetailsFormData {
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

export function billingDetailsPayload(data: BillingDetailsFormData): Record<string, unknown> {
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
        currency_id: nullIfBlank(String(data.currency_id)),
        default_vat_type_id: nullIfBlank(String(data.default_vat_type_id)),
        _method: 'patch',
    };
}
