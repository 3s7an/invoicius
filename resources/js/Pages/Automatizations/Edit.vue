<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AutomatizationForm from '@/Pages/Automatizations/Components/AutomatizationForm.vue';
import PageHeader from '@/Components/PageHeader.vue';
import { Head, Link } from '@inertiajs/vue3';
import Button from 'primevue/button';
import { useI18n } from 'vue-i18n';
import type { Automatization, AutomatizationTypeOption } from './Utils/types';
import type { RecipientResource } from '@/types';

const { t } = useI18n();

defineProps<{
    automatization: Automatization
    recipients: RecipientResource[]
    automatization_types: AutomatizationTypeOption[]
}>();
</script>

<template>
    <Head :title="t('automatizations.form.editTitle')" />
    <AuthenticatedLayout>
        <div class="space-y-8">
            <PageHeader :title="t('automatizations.form.editTitle')">
                <template #actions>
                    <Link :href="route('automatizations.index')">
                        <Button
                            :label="t('automatizations.backToAutomatizations')"
                            icon="pi pi-arrow-left"
                            class="p-button-raised p-button-sm"
                        />
                    </Link>
                </template>
            </PageHeader>

            <AutomatizationForm
                mode="edit"
                :automatization="automatization"
                :recipients="recipients"
                :automatization_types="automatization_types"
            />
        </div>
    </AuthenticatedLayout>
</template>
