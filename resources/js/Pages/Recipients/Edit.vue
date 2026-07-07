<template>
    <Head :title="t('recipients.form.editTitle', { name: recipient.company_name || recipient.name || t('recipients.form.defaultName') })" />

    <AuthenticatedLayout>
        <RecipientForm mode="edit" :recipient="recipient" />
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import RecipientForm from './Components/RecipientForm.vue';
import type { RecipientResource } from '@/types';

const { t } = useI18n();

defineProps<{
    recipient: RecipientResource
}>();
</script>
