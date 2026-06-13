import type { PageProps as InertiaPageProps } from '@inertiajs/core'

export interface AuthUser {
    id: number
    name: string
    email: string
    street: string | null
    street_num: string | null
    city: string | null
    zip: string | null
    state: string | null
    ico: string | null
    dic: string | null
    ic_dph: string | null
    iban: string | null
    currency_id: number | null
    default_vat_type_id: number | null
}

declare module '@inertiajs/vue3' {
    interface PageProps extends InertiaPageProps {
        auth: { user: AuthUser }
        flash: { success?: string; error?: string }
    }
}
