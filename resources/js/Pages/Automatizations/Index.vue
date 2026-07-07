<script setup lang="ts">
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AutomatizationList from '@/Components/AutomatizationList.vue';
import AutomatizationCardList from '@/Pages/Automatizations/Components/AutomatizationCardList.vue';
import AutomatizationFormModal from '@/Pages/Automatizations/Components/AutomatizationFormModal.vue';
import PageHeader from '@/Components/PageHeader.vue';
import { Head } from '@inertiajs/vue3';
import Button from 'primevue/button';
import { useI18n } from 'vue-i18n';
import type { Automatization, AutomatizationTypeOption } from './Utils/types';
import type { RecipientResource } from '@/types';

const { t } = useI18n();

const props = defineProps<{
    automatizations: Automatization[]
    recipients: RecipientResource[]
    automatization_types: AutomatizationTypeOption[]
    editing_automatization?: Automatization | null
}>();

const showModal = ref(!!props.editing_automatization);
const editingAutomatization = ref<Automatization | null>(props.editing_automatization ?? null);

function openCreate() {
    editingAutomatization.value = null;
    showModal.value = true;
}

function openEdit(automatization: Automatization) {
    editingAutomatization.value = automatization;
    showModal.value = true;
}
</script>

<template>
    <Head :title="t('automatizations.pageTitle')" />

    <AuthenticatedLayout>
        <div class="space-y-6">
            <PageHeader :title="t('automatizations.pageTitle')">
                <template #actions>
                    <Button
                        :label="t('automatizations.newAutomatization')"
                        icon="pi pi-plus"
                        class="p-button-raised p-button-sm"
                        @click="openCreate"
                    />
                </template>
            </PageHeader>

            <div v-if="automatizations.length">
                <AutomatizationCardList
                    class="lg:hidden"
                    :automatizations="automatizations"
                    @edit="openEdit"
                />
                <div class="hidden overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm lg:block">
                    <AutomatizationList :automatizations="automatizations" @edit="openEdit" />
                </div>
            </div>
            <div v-else class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <p class="app-muted">
                    {{ t('automatizations.emptyState') }}
                </p>
            </div>
        </div>
    </AuthenticatedLayout>

    <AutomatizationFormModal
        :key="editingAutomatization?.id ?? 'create'"
        :show="showModal"
        :automatization="editingAutomatization"
        :recipients="recipients"
        :automatization-types="automatization_types"
        @close="showModal = false"
    />
</template>
