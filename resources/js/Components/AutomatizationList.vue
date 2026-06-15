<script setup lang="ts">
import { formatDate } from '@/utils/formatters';
import Button from 'primevue/button';
import { useAutomatizationActions } from '@/Pages/Automatizations/Composables/automatizationActions';
import type { Automatization } from '@/Pages/Automatizations/Utils/types';

const { toggleActive, confirmDelete } = useAutomatizationActions();

defineProps<{
    automatizations: Automatization[]
}>();

const emit = defineEmits<{ edit: [Automatization] }>();

const AUTO_ICON: Record<string, string> = {
    invoice: 'pi-file',
    report: 'pi-chart-bar',
    reminder: 'pi-bell',
};
</script>

<template>
    <table class="w-full border-collapse text-sm" style="min-width:580px">
        <thead>
            <tr class="border-b border-gray-200">
                <th v-for="h in ['Typ','Klient','Nasledujúce spustenie','Stav','']" :key="h"
                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.05em] text-gray-500 whitespace-nowrap"
>
                    {{ h }}
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="a in automatizations" :key="a.id" class="border-b border-gray-100 last:border-0 hover:bg-gray-50/50 transition-colors">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2.5">
                        <span class="inline-flex h-[34px] w-[34px] shrink-0 items-center justify-center rounded-md bg-emerald-50 text-emerald-700">
                            <i :class="`pi ${AUTO_ICON[a.type] ?? 'pi-bolt'}`" class="text-[15px]" />
                        </span>
                        <span class="font-medium text-gray-900">{{ a.type_label }}</span>
                    </div>
                </td>
                <td class="px-4 py-3 text-gray-700">{{ a.recipient_label || '—' }}</td>
                <td class="px-4 py-3 text-gray-500 whitespace-nowrap">{{ a.is_active && a.date_trigger ? formatDate(a.date_trigger) : '—' }}</td>
                <td class="px-4 py-3">
                    <span
                        class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium"
                        :class="a.is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500'"
                    >
                        {{ a.is_active ? 'Aktívne' : 'Neaktívne' }}
                    </span>
                </td>
                <td class="px-4 py-3 text-right whitespace-nowrap">
                    <Button
                        :icon="a.is_active ? 'pi pi-pause' : 'pi pi-play'"
                        class="p-button-text p-button-sm"
                        :title="a.is_active ? 'Deaktivovať' : 'Aktivovať'"
                        @click="toggleActive(a)"
                    />
                    <Button icon="pi pi-pencil" class="p-button-text p-button-sm" title="Upraviť" @click="emit('edit', a)" />
                    <Button icon="pi pi-trash" class="p-button-text p-button-sm p-button-danger" title="Zmazať" @click="confirmDelete(a)" />
                </td>
            </tr>
        </tbody>
    </table>
</template>
