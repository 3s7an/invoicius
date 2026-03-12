<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BillingDetailsForm from '@/Components/BillingDetailsForm.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import InvoiceSettings from '@/Components/InvoiceSettings.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    currencies: {
        type: Array,
        default: () => [],
    },
    invoice_colors: {
        type: Array,
        default: () => [],
    },
    vat_types: {
        type: Array,
        default: () => [],
    },
});

const user = usePage().props.auth?.user;

const invoiceSettingsForm = useForm({
    invoice_color_id: user?.invoice_color_id ?? '',
    company_logo: null,
    _method: 'patch',
});

const billingDetailsForm = useForm({
    street: user?.street ?? '',
    street_num: user?.street_num ?? '',
    city: user?.city ?? '',
    zip: user?.zip ?? '',
    state: user?.state ?? '',
    ico: user?.ico ?? '',
    dic: user?.dic ?? '',
    ic_dph: user?.ic_dph ?? '',
    currency_id: user?.currency_id ?? '',
    default_vat_type_id: user?.default_vat_type_id ?? '',
    _method: 'patch',
});
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <h1 class="mb-6 text-lg font-medium text-gray-900">Profile</h1>
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <form
                    @submit.prevent="invoiceSettingsForm.post(route('profile.invoice-settings.update'), {
    forceFormData: true,
    transform: (data) => ({ ...data, _method: 'patch' })
})"
                >
                    <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                        <h2 class="text-lg font-medium text-gray-900">
                            Invoice settings
                        </h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Company logo and default invoice color.
                        </p>
                        <div class="mt-6">
                            <InvoiceSettings
                                mode="profile"
                                :form="invoiceSettingsForm"
                                :user="user"
                                :invoice-colors="invoice_colors"
                                id-prefix="profile-invoice"
                            />
                        </div>
                        <div class="mt-6 flex items-center gap-4">
                            <PrimaryButton :disabled="invoiceSettingsForm.processing">Save</PrimaryButton>
                            <Transition
                                enter-active-class="transition ease-in-out"
                                enter-from-class="opacity-0"
                                leave-active-class="transition ease-in-out"
                                leave-to-class="opacity-0"
                            >
                                <p v-if="invoiceSettingsForm.recentlySuccessful" class="text-sm text-gray-600">
                                    Saved.
                                </p>
                            </Transition>
                        </div>
                    </div>
                </form>

                <form
                    @submit.prevent="billingDetailsForm.post(route('profile.details.update'), {
    forceFormData: true,
    transform: (data) => ({ ...data, _method: 'patch' })
})"
                >
                    <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                        <BillingDetailsForm
                            :form="billingDetailsForm"
                                id-prefix="profile-billing"
                            :currencies="currencies"
                            :vat-types="vat_types"
                            class="max-w-xl"
                        />
                    </div>
                </form>

                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <DeleteUserForm class="max-w-xl" />
                </div>
        </div>
    </AuthenticatedLayout>
</template>
