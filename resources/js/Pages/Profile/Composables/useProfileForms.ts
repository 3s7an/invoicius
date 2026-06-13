import { useForm, usePage } from '@inertiajs/vue3';
import type { AuthUser } from '@/types/inertia';
import {
    billingDetailsPayload,
    createBillingDetailsDefaults,
    createInvoiceSettingsDefaults,
} from '../Utils/profileFormDefaults';

export function useProfileForms() {
    const user = (usePage().props as { auth?: { user: AuthUser } }).auth?.user ?? null;

    const invoiceSettingsForm = useForm(createInvoiceSettingsDefaults());
    const billingDetailsForm = useForm(createBillingDetailsDefaults(user));

    function submitInvoiceSettings(): void {
        invoiceSettingsForm
            .transform((data) => ({ ...data, _method: 'patch' }))
            .post(route('profile.invoice-settings.update'), {
                forceFormData: true,
                preserveScroll: true,
            });
    }

    function submitBillingDetails(): void {
        billingDetailsForm
            .transform(billingDetailsPayload)
            .post(route('profile.details.update'), {
                preserveScroll: true,
            });
    }

    return {
        user,
        invoiceSettingsForm,
        billingDetailsForm,
        submitInvoiceSettings,
        submitBillingDetails,
    };
}
