<template>
    <form @submit.prevent="onSubmit">
        <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
            <h2 class="text-lg font-medium text-gray-900">{{ t('profile.invoiceSettings.title') }}</h2>
            <p class="mt-1 text-sm text-gray-600">{{ t('profile.invoiceSettings.description') }}</p>

            <div class="mt-6">
                <InvoiceSettings mode="profile" :form="form" :user="user" id-prefix="profile-invoice" />
            </div>

            <div class="mt-6 flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">{{ t('common.save') }}</PrimaryButton>
                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">{{ t('common.saved') }}</p>
                </Transition>
            </div>
        </div>
    </form>
</template>

<script setup lang="ts">
import InvoiceSettings from '@/Pages/Invoices/Components/InvoiceSettings.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useI18n } from 'vue-i18n';
import type { InertiaForm } from '@inertiajs/vue3';
import type { InvoiceSettingsFormData } from '../Utils/profileFormDefaults';
import type { AuthUser } from '@/types/inertia';

const { t } = useI18n();

defineProps<{
    form: InertiaForm<InvoiceSettingsFormData>
    user: AuthUser | null
    onSubmit: () => void
}>();
</script>


