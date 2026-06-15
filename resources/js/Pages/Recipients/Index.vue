<script setup lang="ts">
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import RecipientCardList from '@/Pages/Recipients/Components/RecipientCardList.vue';
import RecipientFormModal from '@/Pages/Recipients/Components/RecipientFormModal.vue';
import { Head, router } from '@inertiajs/vue3';
import Button from 'primevue/button';
import type { RecipientResource } from '@/types';

const props = defineProps<{
    recipients: RecipientResource[]
    editing_recipient?: RecipientResource | null
}>();

const showModal = ref(!!props.editing_recipient);
const editingRecipient = ref<RecipientResource | null>(props.editing_recipient ?? null);

function openCreate() {
    editingRecipient.value = null;
    showModal.value = true;
}

function openEdit(recipient: RecipientResource) {
    editingRecipient.value = recipient;
    showModal.value = true;
}

function displayName(recipient: RecipientResource): string {
    return recipient.company_name || recipient.name || '—';
}

function confirmDeleteRecipient(recipient: RecipientResource): void {
    if (!confirm(`Zmazať „${displayName(recipient)}"?`)) return;
    router.delete(route('recipients.destroy', recipient.id));
}
</script>

<template>
    <Head title="Klienti" />

    <AuthenticatedLayout>
        <div class="space-y-6">
            <PageHeader title="Klienti">
                <template #actions>
                    <Button label="Nový klient" icon="pi pi-plus" class="p-button-raised p-button-sm" @click="openCreate" />
                </template>
            </PageHeader>
            <RecipientCardList class="lg:hidden" :recipients="recipients" @edit="openEdit" />

            <div class="hidden lg:block overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm overflow-x-auto">
                <table class="w-full border-collapse text-sm" style="min-width:580px">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th v-for="h in ['Názov / obchodné meno','Mesto','Ulica','Číslo ulice','']" :key="h"
                                class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.05em] text-gray-500 whitespace-nowrap"
>
                                {{ h }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="recipient in recipients" :key="recipient.id" class="border-b border-gray-100 last:border-0 hover:bg-gray-50/50 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ displayName(recipient) }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ recipient.city || '—' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ recipient.street || '—' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ recipient.street_num || '—' }}</td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <Button icon="pi pi-pencil" class="p-button-text p-button-sm" title="Upraviť" @click="openEdit(recipient)" />
                                <Button icon="pi pi-trash" class="p-button-text p-button-sm p-button-danger" title="Zmazať" @click="confirmDeleteRecipient(recipient)" />
                            </td>
                        </tr>
                        <tr v-if="recipients.length === 0">
                            <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500">Zatiaľ nemáte žiadnych klientov.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>

    <RecipientFormModal
        :key="editingRecipient?.id ?? 'create'"
        :show="showModal"
        :recipient="editingRecipient"
        @close="showModal = false"
    />
</template>
