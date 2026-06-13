import type { PageProps as InertiaPageProps } from '@inertiajs/core'
import type { route as Ziggy } from 'ziggy-js'

declare global {
    const route: typeof Ziggy
}

declare module '@vue/runtime-core' {
    interface ComponentCustomProperties {
        route: typeof Ziggy
    }
}

export interface AuthUser {
    id: number
    name: string
    email: string
    company_name: string | null
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
