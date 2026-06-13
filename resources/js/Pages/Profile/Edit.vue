<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import { Head } from '@inertiajs/vue3';
import DeleteUserForm from './Components/DeleteUserForm.vue';
import BillingDetailsForm from './Components/BillingDetailsForm.vue';
import ProfileInvoiceSettingsSection from './Components/ProfileInvoiceSettingsSection.vue';
import UpdatePasswordForm from './Components/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Components/UpdateProfileInformationForm.vue';
import { useProfileForms } from './Composables/useProfileForms';

defineProps<{
    mustVerifyEmail?: boolean
    status?: string
    currencies: App.Http.Resources.CurrencyResource[]
    vat_types: App.Http.Resources.VatTypeResource[]
}>();

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
            <PageHeader title="Profil" />
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
