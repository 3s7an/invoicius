<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div class="mb-4">
        <div class="flex items-start justify-between gap-3">
            <h1 class="min-w-0 flex-1 text-2xl font-semibold leading-tight tracking-tight text-gray-900 sm:text-3xl">
                {{ title }}
            </h1>

            <div
                v-if="$slots.actions"
                class="flex shrink-0 flex-wrap items-center justify-end gap-2"
            >
                <slot name="actions" />
            </div>
        </div>

        <p v-if="$slots.subtitle" class="mt-1 text-sm text-gray-600">
            <slot name="subtitle" />
        </p>
    </div>
</template>
