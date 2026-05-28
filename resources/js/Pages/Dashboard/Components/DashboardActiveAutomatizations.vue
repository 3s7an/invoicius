<script setup>
import { Link } from '@inertiajs/vue3';

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

        <div v-else class="mt-4 space-y-3">
            <div
                v-for="a in active_automatizations"
                :key="a.id"
                class="rounded-xl bg-gray-50/60 p-4 ring-1 ring-gray-200/60"
            >
                <div class="flex items-start justify-between gap-4">
                    <p class="text-sm font-semibold text-gray-900">{{ a.type }}</p>
                    <p class="text-xs text-gray-500">next: {{ a.date_trigger || '—' }}</p>
                </div>
                <p class="mt-1 text-sm text-gray-600">
                    {{ a.recipient_label ? `Klient: ${a.recipient_label}` : 'Pre všetkých klientov' }}
                    <span v-if="a.due_offset_days != null"> · offset: {{ a.due_offset_days }}</span>
                </p>
            </div>
        </div>
    </div>
</template>

