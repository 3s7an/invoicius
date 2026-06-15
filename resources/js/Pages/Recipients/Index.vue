<script setup lang="ts">
import { computed, ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import RecipientCardList from '@/Pages/Recipients/Components/RecipientCardList.vue';
import RecipientFormModal from '@/Pages/Recipients/Components/RecipientFormModal.vue';
import { Head, router } from '@inertiajs/vue3';
import Button from 'primevue/button';
import { formatAmount } from '@/utils/formatters';
import type { RecipientResource } from '@/types';

const props = defineProps<{
    recipients: RecipientResource[]
    editing_recipient?: RecipientResource | null
}>();

const search = ref('');
const showModal = ref(!!props.editing_recipient);
const editingRecipient = ref<RecipientResource | null>(props.editing_recipient ?? null);

const filtered = computed(() => {
    const q = search.value.trim().toLowerCase();
    if (!q) return props.recipients;
    return props.recipients.filter((r) => {
        const name = (r.company_name || r.name || '').toLowerCase();
        const email = (r.email || '').toLowerCase();
        const ico = (r.ico || '').toLowerCase();
        return name.includes(q) || email.includes(q) || ico.includes(q);
    });
});

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

function initials(recipient: RecipientResource): string {
    const name = displayName(recipient);
    const words = name.split(/[\s.]+/).filter(Boolean);
    if (words.length >= 2) return (words[0][0] + words[1][0]).toUpperCase();
    return name.slice(0, 2).toUpperCase();
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
            <!-- Header -->
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h1 class="text-3xl font-semibold tracking-tight text-gray-900">Klienti</h1>
                    <p class="mt-1 text-sm text-gray-500">{{ recipients.length }} klientov celkom</p>
                </div>
                <Button label="Nový klient" icon="pi pi-plus" class="p-button-raised p-button-sm shrink-0" @click="openCreate" />
            </div>

            <!-- Search -->
            <div class="relative w-full max-w-xs">
                <i class="pi pi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm" aria-hidden="true" />
                <input
                    v-model="search"
                    type="text"
                    placeholder="Vyhľadať klienta..."
                    class="w-full rounded-lg border border-gray-200 bg-white py-2 pl-9 pr-3 text-sm text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                >
            </div>

            <!-- Mobile cards -->
            <RecipientCardList class="lg:hidden" :recipients="filtered" @edit="openEdit" />

            <!-- Desktop table -->
            <div class="hidden lg:block overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm overflow-x-auto">
                <table class="w-full border-collapse text-sm" style="min-width:680px">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.05em] text-gray-500">KLIENT</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.05em] text-gray-500">IČO</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.05em] text-gray-500">MESTO</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-[0.05em] text-gray-500">FAKTÚRY</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-[0.05em] text-gray-500">FAKTUROVANÉ</th>
                            <th class="px-4 py-3" />
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="recipient in filtered"
                            :key="recipient.id"
                            class="border-b border-gray-100 last:border-0 hover:bg-gray-50/50 transition-colors"
                        >
                            <!-- KLIENT: avatar + name + email -->
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-xs font-bold text-emerald-700">
                                        {{ initials(recipient) }}
                                    </span>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-gray-900 truncate">{{ displayName(recipient) }}</p>
                                        <p v-if="recipient.email" class="text-xs text-gray-500 truncate">{{ recipient.email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-700 whitespace-nowrap">{{ recipient.ico || '—' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ recipient.city || '—' }}</td>
                            <td class="px-4 py-3 text-right tabular-nums text-gray-700">{{ recipient.invoice_count }}</td>
                            <td class="px-4 py-3 text-right font-semibold tabular-nums text-gray-900 whitespace-nowrap">
                                {{ formatAmount(recipient.total_invoiced) }} €
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <Button icon="pi pi-pencil" class="p-button-text p-button-sm" title="Upraviť" @click="openEdit(recipient)" />
                                <Button icon="pi pi-trash" class="p-button-text p-button-sm p-button-danger" title="Zmazať" @click="confirmDeleteRecipient(recipient)" />
                            </td>
                        </tr>
                        <tr v-if="filtered.length === 0">
                            <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">
                                {{ search ? 'Žiadny klient nezodpovedá vyhľadávaniu.' : 'Zatiaľ nemáte žiadnych klientov.' }}
                            </td>
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
