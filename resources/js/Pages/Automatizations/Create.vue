<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AutomatizationForm from '@/Pages/Automatizations/Components/AutomatizationForm.vue';
import PageHeader from '@/Components/PageHeader.vue';
import { Head, Link } from '@inertiajs/vue3';
import Button from 'primevue/button';

defineProps({
    recipients: {
        type: Array,
        default: () => [],
    },
    automatization_types: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <Head title="Nová automatizácia" />
    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <PageHeader title="Nová automatizácia">
                <template #actions>
                    <Link :href="route('automatizations.index')">
                        <Button
                            label="Späť na automatizácie"
                            icon="pi pi-arrow-left"
                            class="p-button-raised p-button-sm"
                        />
                    </Link>
                </template>
            </PageHeader>

            <AutomatizationForm
                mode="create"
                :recipients="recipients"
                :automatization_types="automatization_types"
            />
        </div>
    </AuthenticatedLayout>
</template>

