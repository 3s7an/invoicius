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
    email_verified_at: string | null
    company_name: string | null
    company_logo: { url: string } | null
    company_logo_id: number | null
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

export type AppLocale = 'sk' | 'en'

declare module '@inertiajs/vue3' {
    interface PageProps extends InertiaPageProps {
        auth: { user: AuthUser }
        flash: { success?: string; error?: string }
        locale: AppLocale
    }
}
