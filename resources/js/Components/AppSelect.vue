<script setup>
import Select from 'primevue/select';

const model = defineModel({
    type: [String, Number, null],
    default: null,
});

defineProps({
    inputId: {
        type: String,
        default: undefined,
    },
    options: {
        type: Array,
        default: () => [],
    },
    optionLabel: {
        type: String,
        default: 'label',
    },
    optionValue: {
        type: String,
        default: 'value',
    },
    placeholder: {
        type: String,
        default: undefined,
    },
    showClear: {
        type: Boolean,
        default: false,
    },
    filter: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    size: {
        type: String,
        default: undefined,
    },
});
</script>

<template>
    <Select
        :inputId="inputId"
        v-model="model"
        :options="options"
        :optionLabel="optionLabel"
        :optionValue="optionValue"
        :placeholder="placeholder"
        :showClear="showClear"
        :filter="filter"
        :disabled="disabled"
        :size="size"
        fluid
        class="w-full"
    />
</template>
