import { useForm, usePage } from '@inertiajs/vue3';
import {
    billingDetailsPayload,
    createBillingDetailsDefaults,
    createInvoiceSettingsDefaults,
} from '../Utils/profileFormDefaults';

export function useProfileForms() {
    const user = usePage().props.auth?.user ?? null;

    const invoiceSettingsForm = useForm(createInvoiceSettingsDefaults());
    const billingDetailsForm = useForm(createBillingDetailsDefaults(user));

    function submitInvoiceSettings() {
        invoiceSettingsForm.post(route('profile.invoice-settings.update'), {
            forceFormData: true,
            transform: (data) => ({ ...data, _method: 'patch' }),
            preserveScroll: true,
        });
    }

    function submitBillingDetails() {
        billingDetailsForm.post(route('profile.details.update'), {
            transform: billingDetailsPayload,
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

