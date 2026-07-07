<script setup>
import { onMounted, ref } from 'vue';
import Select from 'primevue/select';

const model = defineModel({
    type: [String, Number, null],
    default: null,
});

// Native <dialog> elements (used by our Modal component) promote themselves to the
// browser's top layer when opened via showModal(). Anything teleported to <body>
// (like this Select's overlay panel) then ends up outside that top layer, so clicks
// pass through to the page behind the modal instead of hitting the dropdown options.
// Appending the overlay to the dialog itself keeps it inside the same top layer.
const selectRef = ref(null);
const appendTo = ref('body');

onMounted(() => {
    const dialogEl = selectRef.value?.$el?.closest?.('dialog');
    if (dialogEl) {
        appendTo.value = dialogEl;
    }
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
        ref="selectRef"
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
        :appendTo="appendTo"
        fluid
        class="w-full"
    />
</template>
