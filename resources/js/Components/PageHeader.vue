<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div class="mb-4 mt-4 flex flex-wrap items-end justify-between gap-4">
        <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">
            {{ title }}
        </h1>
        <div v-if="$slots.actions" class="shrink-0">
            <slot name="actions" />
        </div>
    </div>
</template>

