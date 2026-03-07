<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    invoice: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Head :title="'Edit invoice ' + (invoice?.number ?? '')" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-wrap items-center gap-3">
                <Link :href="route('invoices')" class="text-sm text-gray-500 hover:text-gray-700">← Invoices</Link>
                <h1 class="text-lg font-medium text-gray-900">Edit invoice {{ invoice?.number }}</h1>
            </div>
                <p class="text-gray-600">Edit form – to be implemented.</p>
        </div>
    </AuthenticatedLayout>
</template>
