<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AutomatizationList from '@/Components/AutomatizationList.vue';
import { Head, Link } from '@inertiajs/vue3';
import Button from 'primevue/button';

defineProps({
    automatizations: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <Head title="Automatizácie" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                <h1 class="app-page-title">Automatizácie</h1>
                <Link :href="route('automatizations.create')">
                    <Button
                        label="Nová automatizácia"
                        icon="pi pi-plus"
                        class="p-button-raised p-button-sm"
                    />
                </Link>
            </div>

            <div v-if="automatizations.length" class="app-card overflow-hidden">
                <AutomatizationList :automatizations="automatizations" />
            </div>
            <div v-else class="app-card p-6">
                <p class="app-muted">
                    Zatiaľ nemáte žiadne automatizácie. Vytvorte jednu na automatické generovanie faktúr alebo mesačný report.
                </p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
