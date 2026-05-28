<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AutomatizationForm from '@/Pages/Automatizations/Components/AutomatizationForm.vue';
import { Head, Link } from '@inertiajs/vue3';
import Button from 'primevue/button';

defineProps({
    automatization: {
        type: Object,
        required: true,
    },
    recipients: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <Head title="Upraviť automatizáciu" />
    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h1 class="app-page-title">Upraviť automatizáciu</h1>
                <Link :href="route('automatizations.index')">
                    <Button label="Späť na automatizácie" icon="pi pi-arrow-left" class="p-button-raised p-button-sm" />
                </Link>
            </div>

            <AutomatizationForm
                mode="edit"
                :automatization="automatization"
                :recipients="recipients"
            />
        </div>
    </AuthenticatedLayout>
</template>

