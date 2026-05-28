<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AutomatizationList from '@/Components/AutomatizationList.vue';
import PageHeader from '@/Components/PageHeader.vue';
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
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <PageHeader title="Automatizácie">
                <template #actions>
                    <Link :href="route('automatizations.create')">
                        <Button
                            label="Nová automatizácia"
                            icon="pi pi-plus"
                            class="p-button-raised p-button-sm"
                        />
                    </Link>
                </template>
            </PageHeader>

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
