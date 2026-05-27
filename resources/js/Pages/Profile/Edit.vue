<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import DeleteUserForm from './Components/DeleteUserForm.vue';
import BillingDetailsForm from './Components/BillingDetailsForm.vue';
import ProfileInvoiceSettingsSection from './Components/ProfileInvoiceSettingsSection.vue';
import UpdatePasswordForm from './Components/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Components/UpdateProfileInformationForm.vue';
import { useProfileForms } from './Composables/useProfileForms';

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
    vat_types: {
        type: Array,
        default: () => [],
    },
});

const {
    user,
    invoiceSettingsForm,
    billingDetailsForm,
    submitInvoiceSettings,
    submitBillingDetails,
} = useProfileForms();
</script>

<template>
    <Head title="Profil" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <h1 class="mb-6 text-lg font-medium text-gray-900">Profil</h1>
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <ProfileInvoiceSettingsSection
                    :form="invoiceSettingsForm"
                    :user="user"
                    :on-submit="submitInvoiceSettings"
                />

                <form @submit.prevent="submitBillingDetails">
                    <BillingDetailsForm
                        :form="billingDetailsForm"
                        id-prefix="profile-billing"
                        :currencies="currencies"
                        :vat-types="vat_types"
                    />
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
