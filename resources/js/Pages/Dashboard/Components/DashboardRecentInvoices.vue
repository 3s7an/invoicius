<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import DashboardPanel from '@/Pages/Dashboard/Components/DashboardPanel.vue';
import { formatAmount, formatDate } from '@/utils/formatters';
import type { InvoiceResource } from '@/types';

defineProps<{
    recent_invoices: InvoiceResource[]
    currency_symbol: string
}>();

function statusBadge(code: string): { bg: string; text: string } {
    if (code === 'paid') return { bg: 'var(--status-paid-soft)', text: 'var(--status-paid-text)' };
    if (code === 'overdue') return { bg: 'var(--status-overdue-soft)', text: 'var(--status-overdue-text)' };
    if (code === 'draft') return { bg: 'var(--status-draft-soft)', text: 'var(--status-draft-text)' };
    if (code === 'sent') return { bg: 'var(--status-sent-soft)', text: 'var(--status-sent-text)' };
    return { bg: 'var(--status-awaiting-soft)', text: 'var(--status-awaiting-text)' };
}
</script>

<template>
    <DashboardPanel title="Posledné faktúry">
        <template #actions>
            <Link
                :href="route('invoices')"
                class="flex items-center gap-1 text-sm font-medium text-gray-500 hover:text-gray-700"
            >
                Zobraziť všetky
                <i class="pi pi-arrow-right text-xs" aria-hidden="true" />
            </Link>
        </template>

        <div
            v-if="recent_invoices.length === 0"
            class="flex flex-col items-center justify-center px-5 py-10 text-center"
        >
            <i class="pi pi-inbox mb-3 text-3xl text-gray-300" aria-hidden="true" />
            <p class="text-sm text-gray-500">Zatiaľ tu nie sú žiadne faktúry.</p>
        </div>

        <table v-else style="width:100%; border-collapse:collapse; font-size:14px;">
            <tbody>
                <tr
                    v-for="invoice in recent_invoices"
                    :key="invoice.id"
                    style="border-bottom:1px solid var(--border-subtle);"
                >
                    <td style="padding:12px 20px;">
                        <div style="font-weight:600; color:var(--text-strong);">{{ invoice.number || '—' }}</div>
                        <div style="font-size:12px; color:var(--text-muted);">
                            {{ invoice.created_at ? formatDate(invoice.created_at) : '—' }}
                        </div>
                    </td>
                    <td style="padding:12px 8px; color:var(--text-body);">{{ invoice.recipient_name || '—' }}</td>
                    <td style="padding:12px 8px;">
                        <span
                            v-if="invoice.invoice_status"
                            style="display:inline-flex; align-items:center; border-radius:9999px; padding:2px 8px; font-size:12px; font-weight:500; white-space:nowrap;"
                            :style="{ background: statusBadge(invoice.invoice_status.code).bg, color: statusBadge(invoice.invoice_status.code).text }"
                        >
                            {{ invoice.invoice_status.name }}
                        </span>
                        <span v-else style="color:var(--text-muted);">—</span>
                    </td>
                    <td style="padding:12px 20px; text-align:right; font-weight:600; color:var(--text-strong); font-variant-numeric:tabular-nums; white-space:nowrap;">
                        {{ formatAmount(invoice.total_price) }} {{ currency_symbol }}
                    </td>
                </tr>
            </tbody>
        </table>
    </DashboardPanel>
</template>
