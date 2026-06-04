<script setup>
import { Link } from '@inertiajs/vue3';
import { formatDate } from '@/utils/formatters';

defineProps({
    active_automatizations: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-base font-semibold text-gray-900">Aktívne automatizácie</h2>
            <Link
                :href="route('automatizations.index')"
                class="text-sm font-semibold text-emerald-700 hover:text-emerald-800"
            >
                Spravovať
            </Link>
        </div>

        <div v-if="active_automatizations.length === 0" class="mt-4 rounded-xl bg-gray-50 p-6 text-sm text-gray-600">
            Zatiaľ nemáš žiadne aktívne automatizácie.
        </div>

        <ul v-else class="mt-4 space-y-4">
            <li v-for="a in active_automatizations" :key="a.id">
                <p class="text-sm font-semibold text-gray-900">{{ a.name }}</p>
                <p class="text-xs text-gray-500">
                    ďalšie spustenie: {{ a.date_trigger ? formatDate(a.date_trigger) : '—' }}
                </p>
                <p class="mt-0.5 text-sm text-gray-600">
                    {{ a.recipient_label ? `Klient: ${a.recipient_label}` : 'Pre všetkých klientov' }}
                </p>
            </li>
        </ul>
    </div>
</template>

