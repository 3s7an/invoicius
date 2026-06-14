<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import Button from 'primevue/button';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Tag from 'primevue/tag';
import DashboardPanel from '@/Pages/Dashboard/Components/DashboardPanel.vue';
import { formatDate } from '@/utils/formatters';
import type { ActiveAutomatization } from '../Utils/types';

defineProps<{
    active_automatizations: ActiveAutomatization[]
}>();
</script>

<template>
    <DashboardPanel title="Aktívne automatizácie">
        <template #actions>
            <Link :href="route('automatizations.index')">
                <Button label="Spravovať" icon="pi pi-cog" link size="small" />
            </Link>
        </template>

        <div
            v-if="active_automatizations.length === 0"
            class="flex flex-col items-center justify-center px-5 py-10 text-center sm:px-6"
        >
            <i class="pi pi-bolt mb-3 text-3xl text-gray-300" aria-hidden="true" />
            <p class="text-sm text-gray-600">Zatiaľ nemáš žiadne aktívne automatizácie.</p>
            <Link :href="route('automatizations.create')" class="mt-4">
                <Button label="Nová automatizácia" icon="pi pi-plus" size="small" />
            </Link>
        </div>

        <template v-else>
            <ul class="divide-y divide-gray-200 px-5 sm:px-6 md:hidden">
                <li
                    v-for="automatization in active_automatizations"
                    :key="automatization.id"
                    class="py-3 first:pt-4 last:pb-4"
                >
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-900">{{ automatization.type_label }}</p>
                            <p class="mt-0.5 truncate text-xs text-gray-500">
                                {{ automatization.recipient_label || 'Pre všetkých klientov' }}
                            </p>
                            <p class="mt-1 text-xs text-gray-500">
                                Ďalšie:
                                {{ automatization.date_trigger ? formatDate(automatization.date_trigger) : '—' }}
                            </p>
                        </div>
                        <Tag value="Aktívne" severity="success" rounded class="shrink-0" />
                    </div>
                </li>
            </ul>

            <div class="hidden overflow-hidden md:block">
                <DataTable
                    :value="active_automatizations"
                    size="small"
                    class="text-sm"
                >
                    <Column field="type_label" header="Typ">
                        <template #body="{ data }">
                            <span class="text-sm font-medium text-gray-900">{{ data.type_label }}</span>
                        </template>
                    </Column>
                    <Column field="recipient_label" header="Klient">
                        <template #body="{ data }">
                            <span class="block truncate text-gray-600">
                                {{ data.recipient_label || 'Pre všetkých klientov' }}
                            </span>
                        </template>
                    </Column>
                    <Column field="date_trigger" header="Ďalšie spustenie">
                        <template #body="{ data }">
                            {{ data.date_trigger ? formatDate(data.date_trigger) : '—' }}
                        </template>
                    </Column>
                    <Column header="Stav">
                        <template #body>
                            <Tag value="Aktívne" severity="success" rounded />
                        </template>
                    </Column>
                </DataTable>
            </div>
        </template>
    </DashboardPanel>
</template>
