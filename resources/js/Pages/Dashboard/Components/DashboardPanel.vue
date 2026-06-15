<script setup lang="ts">
defineProps<{
    title: string
}>();
</script>

<template>
    <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <header class="flex items-center justify-between gap-3 border-b border-gray-100 px-5 py-3.5">
            <h2 class="text-sm font-semibold text-gray-900">{{ title }}</h2>
            <slot name="actions" />
        </header>

        <div>
            <slot />
        </div>
    </section>
</template>
