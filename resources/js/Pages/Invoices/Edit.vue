<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { formatDate } from '@/utils/formatters';

const props = defineProps({
    invoice: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Head :title="'Invoice ' + (invoice?.number ?? '')" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-wrap items-center gap-3">
                <Link :href="route('invoices')" class="text-sm text-gray-500 hover:text-gray-700">← Invoices</Link>
                <h1 class="text-lg font-medium text-gray-900">Invoice {{ invoice?.number }}</h1>
            </div>
            <div class="rounded-lg bg-white p-6 shadow">
                <dl class="grid gap-4 sm:grid-cols-2">
                    <div><dt class="text-sm text-gray-500">Invoice number</dt><dd class="mt-1 font-medium">{{ invoice.number }}</dd></div>
                    <div><dt class="text-sm text-gray-500">Variable symbol</dt><dd class="mt-1">{{ invoice.varsym ?? '—' }}</dd></div>
                    <div><dt class="text-sm text-gray-500">Issue date</dt><dd class="mt-1">{{ formatDate(invoice.issue_date) }}</dd></div>
                    <div><dt class="text-sm text-gray-500">Due date</dt><dd class="mt-1">{{ formatDate(invoice.due_date) }}</dd></div>
                    <div><dt class="text-sm text-gray-500">Recipient</dt><dd class="mt-1">{{ invoice.recipient_name ?? '—' }}</dd></div>
                    <div><dt class="text-sm text-gray-500">Total</dt><dd class="mt-1 font-semibold">{{ Number(invoice.total_price ?? 0).toFixed(2) }} €</dd></div>
                </dl>

                <div v-if="invoice.items && invoice.items.length" class="mt-8 border-t pt-6">
                    <h3 class="mb-3 text-base font-medium text-gray-900">Items</h3>
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium uppercase text-gray-500">Name</th>
                                <th class="px-3 py-2 text-right text-xs font-medium uppercase text-gray-500">Qty</th>
                                <th class="px-3 py-2 text-left text-xs font-medium uppercase text-gray-500">Unit</th>
                                <th class="px-3 py-2 text-right text-xs font-medium uppercase text-gray-500">Price</th>
                                <th class="px-3 py-2 text-right text-xs font-medium uppercase text-gray-500">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="item in invoice.items" :key="item.id">
                                <td class="px-3 py-2">{{ item.name }}</td>
                                <td class="px-3 py-2 text-right tabular-nums">{{ Number(item.quantity).toFixed(2) }}</td>
                                <td class="px-3 py-2">{{ item.unit ?? 'pcs' }}</td>
                                <td class="px-3 py-2 text-right tabular-nums">{{ Number(item.unit_price).toFixed(2) }}</td>
                                <td class="px-3 py-2 text-right font-medium tabular-nums">{{ Number(item.line_total).toFixed(2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
