<script setup lang="ts">
defineProps<{
    title: string
}>();
</script>

<template>
    <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <header class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-200 px-5 py-4 sm:px-6">
            <h2 class="text-sm font-medium text-gray-800">{{ title }}</h2>
            <slot name="actions" />
        </header>

        <div class="dashboard-panel-body">
            <slot />
        </div>
    </section>
</template>

<style scoped>
.dashboard-panel-body :deep(.p-datatable) {
    border: none;
    border-radius: 0;
}

.dashboard-panel-body :deep(.p-datatable .p-datatable-thead > tr > th) {
    background-color: transparent;
    border: none;
    color: rgb(55 65 81);
    font-size: 0.875rem;
    font-weight: 500;
}

.dashboard-panel-body :deep(.p-datatable .p-datatable-tbody > tr > td) {
    background-color: transparent;
    border: none;
}
</style>
