<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    recipient: {
        type: Object,
        required: true,
    },
});

function val(v) {
    return v != null && String(v).trim() !== '' ? v : '—';
}
</script>

<template>
    <Head :title="'Odberateľ: ' + (recipient.company_name || recipient.name || 'Detail odberateľa')" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-3">
                    <Link :href="route('recipients.index')" class="text-sm text-gray-500 hover:text-gray-700">← Odberatelia</Link>
                    <h1 class="text-lg font-medium text-gray-900">{{ recipient.company_name || recipient.name || 'Odberateľ' }}</h1>
                </div>
                <Link :href="route('recipients.edit', recipient.id)" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Upraviť</Link>
            </div>
                <div class="rounded-lg bg-white p-6 shadow">
                    <dl class="grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2"><dt class="text-sm text-gray-500">Názov / obchodné meno</dt><dd class="mt-1">{{ val(recipient.company_name || recipient.name) }}</dd></div>
                        <div><dt class="text-sm text-gray-500">Ulica</dt><dd class="mt-1">{{ val(recipient.street) }}</dd></div>
                        <div><dt class="text-sm text-gray-500">Číslo ulice</dt><dd class="mt-1">{{ val(recipient.street_num) }}</dd></div>
                        <div><dt class="text-sm text-gray-500">Mesto</dt><dd class="mt-1">{{ val(recipient.city) }}</dd></div>
                        <div><dt class="text-sm text-gray-500">PSČ</dt><dd class="mt-1">{{ val(recipient.zip) }}</dd></div>
                        <div><dt class="text-sm text-gray-500">Štát / krajina</dt><dd class="mt-1">{{ val(recipient.state) }}</dd></div>
                        <div><dt class="text-sm text-gray-500">IČO</dt><dd class="mt-1">{{ val(recipient.ico) }}</dd></div>
                        <div><dt class="text-sm text-gray-500">DIČ</dt><dd class="mt-1">{{ val(recipient.dic) }}</dd></div>
                        <div><dt class="text-sm text-gray-500">IČ DPH</dt><dd class="mt-1">{{ val(recipient.ic_dph) }}</dd></div>
                        <div class="sm:col-span-2"><dt class="text-sm text-gray-500">IBAN</dt><dd class="mt-1">{{ val(recipient.iban) }}</dd></div>
                    </dl>
                    <div v-if="recipient.invoices && recipient.invoices.length" class="mt-8 border-t pt-6">
                        <h3 class="text-lg font-medium text-gray-900">Faktúry ({{ recipient.invoices.length }})</h3>
                        <ul class="mt-2 space-y-1">
                            <li v-for="inv in recipient.invoices" :key="inv.id">
                                <Link :href="route('invoices.edit', inv.id)" class="text-indigo-600 hover:underline">{{ inv.number }}</Link>
                            </li>
                        </ul>
                    </div>
                </div>
        </div>
    </AuthenticatedLayout>
</template>
