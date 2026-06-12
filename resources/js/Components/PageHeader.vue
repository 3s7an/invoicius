<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div class="mb-4 mt-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="min-w-0">
            <h1 class="text-2xl font-semibold tracking-tight text-gray-900 sm:text-3xl">
                {{ title }}
            </h1>
            <p v-if="$slots.subtitle" class="mt-1 text-sm text-gray-600">
                <slot name="subtitle" />
            </p>
        </div>

        <div
            v-if="$slots.actions"
            class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row sm:items-center"
        >
            <slot name="actions" />
        </div>
    </div>
</template>
