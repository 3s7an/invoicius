<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InvoiceOverviewCards from '@/Components/InvoiceOverviewCards.vue';
import AutomatizationList from '@/Components/AutomatizationList.vue';
import AutomatizationForm from '@/Components/AutomatizationForm.vue';
import { Head } from '@inertiajs/vue3';
import Button from 'primevue/button';

const props = defineProps({
    invoice_stats: {
        type: Object,
        default: () => ({ total_invoiced: 0, paid: 0, awaiting: 0, overdue: 0 }),
    },
    currency_symbol: {
        type: String,
        default: '€',
    },
    automatizations: {
        type: Array,
        default: () => [],
    },
    recipients: {
        type: Array,
        default: () => [],
    },
});

const showForm = ref(false);
const editingAutomatization = ref(null);

function openCreate() {
    editingAutomatization.value = null;
    showForm.value = true;
}

function openEdit(automatization) {
    editingAutomatization.value = automatization;
    showForm.value = true;
}

function closeForm() {
    showForm.value = false;
    editingAutomatization.value = null;
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h1 class="mb-6 text-lg font-medium text-gray-900">Dashboard</h1>
            <InvoiceOverviewCards :stats="invoice_stats" :currency-symbol="currency_symbol" />

            <div class="mt-10">
                <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                    <h2 class="text-lg font-medium text-gray-900">Automatizations</h2>
                    <Button
                        label="New automatization"
                        icon="pi pi-plus"
                        class="p-button-raised p-button-sm"
                        @click="openCreate"
                    />
                </div>

                <div v-if="automatizations.length" class="rounded-xl border border-gray-200 bg-white shadow-sm">
                    <AutomatizationList
                        :automatizations="automatizations"
                        @edit="openEdit"
                    />
                </div>
                <p v-else class="text-sm text-gray-500">
                    No automatizations yet. Create one to automate invoice generation.
                </p>
            </div>
        </div>

        <AutomatizationForm
            :show="showForm"
            :recipients="recipients"
            :editing-automatization="editingAutomatization"
            @close="closeForm"
        />
    </AuthenticatedLayout>
</template>
