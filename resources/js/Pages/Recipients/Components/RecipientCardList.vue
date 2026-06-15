<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import Button from 'primevue/button';
import type { RecipientResource } from '@/types';

defineProps<{
    recipients: RecipientResource[]
}>();

const emit = defineEmits<{ edit: [RecipientResource] }>();

function displayName(recipient: RecipientResource): string {
    return recipient.company_name || recipient.name || '—';
}

function confirmDeleteRecipient(recipient: RecipientResource): void {
    if (!confirm(`Zmazať „${displayName(recipient)}"?`)) return;
    router.delete(route('recipients.destroy', recipient.id));
}
</script>

<template>
    <div v-if="recipients.length === 0" class="rounded-xl border border-gray-200 bg-white p-6 text-sm text-gray-600 shadow-sm">
        Zatiaľ nemáte žiadnych klientov.
    </div>

    <div v-else class="space-y-3">
        <article
            v-for="recipient in recipients"
            :key="recipient.id"
            class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm"
        >
            <p class="text-base font-semibold text-gray-900">{{ displayName(recipient) }}</p>

            <dl class="mt-3 space-y-2 text-sm">
                <div class="flex justify-between gap-4">
                    <dt class="text-gray-500">Mesto</dt>
                    <dd class="text-right font-medium text-gray-900">{{ recipient.city || '—' }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="text-gray-500">Ulica</dt>
                    <dd class="text-right font-medium text-gray-900">{{ recipient.street || '—' }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="text-gray-500">Číslo</dt>
                    <dd class="text-right font-medium text-gray-900">{{ recipient.street_num || '—' }}</dd>
                </div>
            </dl>

            <div class="mt-4 flex items-center justify-end gap-1 border-t border-gray-100 pt-3">
                <Button
                    icon="pi pi-pencil"
                    class="p-button-text p-button-sm"
                    title="Upraviť"
                    @click="emit('edit', recipient)"
                />
                <Button
                    icon="pi pi-trash"
                    class="p-button-text p-button-sm p-button-danger"
                    title="Zmazať"
                    @click="confirmDeleteRecipient(recipient)"
                />
            </div>
        </article>
    </div>
</template>
