<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import DashboardPanel from '@/Pages/Dashboard/Components/DashboardPanel.vue';
import { formatDate } from '@/utils/formatters';
import type { ActiveAutomatization } from '../Utils/types';

const { t } = useI18n();

defineProps<{
    active_automatizations: ActiveAutomatization[]
}>();
</script>

<template>
    <DashboardPanel :title="t('dashboard.activeAutomatizations.title')">
        <template #actions>
            <Link
                :href="route('automatizations.index')"
                class="flex items-center gap-1 text-sm font-medium text-gray-500 hover:text-gray-700"
            >
                {{ t('dashboard.activeAutomatizations.manage') }}
                <i class="pi pi-cog text-xs" aria-hidden="true" />
            </Link>
        </template>

        <div
            v-if="active_automatizations.length === 0"
            class="flex flex-col items-center justify-center px-5 py-10 text-center"
        >
            <i class="pi pi-bolt mb-3 text-3xl text-gray-300" aria-hidden="true" />
            <p class="text-sm text-gray-500">{{ t('dashboard.activeAutomatizations.empty') }}</p>
        </div>

        <table v-else style="width:100%; border-collapse:collapse; font-size:14px;">
            <tbody>
                <tr
                    v-for="automatization in active_automatizations"
                    :key="automatization.id"
                    style="border-bottom:1px solid var(--border-subtle);"
                >
                    <td style="padding:12px 20px;">
                        <div style="font-weight:500; color:var(--text-strong);">{{ automatization.type_label }}</div>
                        <div style="font-size:12px; color:var(--text-muted);">
                            {{ automatization.recipient_label || t('dashboard.activeAutomatizations.allClients') }}
                        </div>
                    </td>
                    <td style="padding:12px 8px; color:var(--text-muted); font-size:13px; white-space:nowrap;">
                        {{ t('dashboard.activeAutomatizations.next') }}: {{ automatization.date_trigger ? formatDate(automatization.date_trigger) : '—' }}
                    </td>
                    <td style="padding:12px 20px; text-align:right;">
                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-700">
                            {{ t('dashboard.activeAutomatizations.active') }}
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </DashboardPanel>
</template>
