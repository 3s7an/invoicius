<script setup>
import { ref, computed, watch } from 'vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    modelValue: {
        type: Array,
        default: () => [],
    },
    currencySymbol: {
        type: String,
        default: '€',
    },
    vatTypes: {
        type: Array,
        default: () => [],
    },
    minRows: {
        type: Number,
        default: 1,
    },
    error: {
        type: String,
        default: '',
    },
});

const emit = defineEmits(['update:modelValue']);

const UNITS = [
    { value: 'pcs', label: 'pcs' },
    { value: 'hrs', label: 'hrs' },
    { value: 'days', label: 'days' },
    { value: 'kg', label: 'kg' },
    { value: 'm', label: 'm' },
    { value: 'm²', label: 'm²' },
];

const vatTypesList = computed(() => (props.vatTypes ?? []));

function defaultItem() {
    return {
        name: '',
        quantity: 1,
        unit: 'pcs',
        unit_price: '',
        vat_type_id: vatTypesList.value?.[0]?.id ?? '',
    };
}

const items = ref(
    props.modelValue?.length
        ? props.modelValue.map((i) => ({ ...defaultItem(), ...i }))
        : [defaultItem()]
);

watch(
    items,
    () => {
        emit(
            'update:modelValue',
            items.value.map((i) => ({ ...i }))
        );
    },
    { deep: true }
);

function addItem() {
    items.value.push(defaultItem());
}

function removeItem(index) {
    if (items.value.length <= props.minRows) return;
    items.value.splice(index, 1);
}

function lineTotal(item) {
    const q = Number.parseFloat(item.quantity) || 0;
    const p = Number.parseFloat(item.unit_price) || 0;
    return q * p;
}

/** MIMO, OSVO = no VAT; otherwise rate from code (23, 19, 5). */
function lineVatAmount(item) {
    const lineWoVat = lineTotal(item);
    const vatId = item.vat_type_id != null ? Number(item.vat_type_id) : null;
    if (vatId == null) return 0;
    const vatType = vatTypesList.value.find((v) => Number(v.id) === vatId);
    if (!vatType) return 0;
    const code = String(vatType.code || '').toUpperCase();
    if (code === 'MIMO' || code === 'OSVO') return 0;
    const rate = Number.parseFloat(vatType.code) || 0;
    return lineWoVat * (rate / 100);
}

const totalWoVat = computed(() =>
    items.value.reduce((sum, item) => sum + lineTotal(item), 0)
);
const totalVat = computed(() =>
    items.value.reduce((sum, item) => sum + lineVatAmount(item), 0)
);
const invoiceTotal = computed(() => totalWoVat.value + totalVat.value);

function formatNum(x) {
    return Number(x).toFixed(2).replace('.', ',');
}
</script>

<template>
    <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-medium text-gray-900">Invoice items</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Add one or more line items with name, quantity, unit and price.
                </p>
                <InputError v-if="error" class="mt-2" :message="error" />
            </div>
            <button
                type="button"
                @click="addItem"
                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
            >
                Add item
            </button>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            scope="col"
                            class="py-3 pl-4 pr-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:pl-6"
                        >
                            Name
                        </th>
                        <th
                            scope="col"
                            class="px-3 py-3 text-right text-xs font-medium uppercase tracking-wide text-gray-500"
                        >
                            Quantity
                        </th>
                        <th
                            scope="col"
                            class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500"
                        >
                            Unit
                        </th>
                        <th
                            scope="col"
                            class="px-3 py-3 text-right text-xs font-medium uppercase tracking-wide text-gray-500"
                        >
                            Unit price
                        </th>
                        <th
                            v-if="vatTypesList.length"
                            scope="col"
                            class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500"
                        >
                            VAT type
                        </th>
                        <th
                            scope="col"
                            class="px-3 py-3 text-right text-xs font-medium uppercase tracking-wide text-gray-500"
                        >
                            Amount excl. VAT
                        </th>
                        <th
                            v-if="vatTypesList.length"
                            scope="col"
                            class="px-3 py-3 text-right text-xs font-medium uppercase tracking-wide text-gray-500"
                        >
                            VAT
                        </th>
                        <th
                            scope="col"
                            class="px-3 py-3 text-right text-xs font-medium uppercase tracking-wide text-gray-500"
                        >
                            Total
                        </th>
                        <th scope="col" class="relative py-3 pl-3 pr-4 sm:pr-6">
                            <span class="sr-only">Remove</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    <tr v-for="(item, index) in items" :key="index">
                        <td class="whitespace-nowrap py-3 pl-4 pr-3 sm:pl-6">
                            <TextInput
                                v-model="item.name"
                                type="text"
                                class="block w-full min-w-[200px]"
                            />
                        </td>
                        <td class="whitespace-nowrap px-3 py-3">
                            <TextInput
                                v-model="item.quantity"
                                type="number"
                                min="0"
                                step="1"
                                class="block w-full text-right"
                            />
                        </td>
                        <td class="whitespace-nowrap px-3 py-3">
                            <select
                                v-model="item.unit"
                                class="block w-full rounded-md border-gray-300 py-1.5 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option
                                    v-for="u in UNITS"
                                    :key="u.value"
                                    :value="u.value"
                                >
                                    {{ u.label }}
                                </option>
                            </select>
                        </td>
                        <td class="whitespace-nowrap px-3 py-3">
                            <TextInput
                                v-model="item.unit_price"
                                type="number"
                                min="0"
                                step="0.01"
                                class="block w-full text-right"
                            />
                        </td>
                        <td v-if="vatTypesList.length" class="whitespace-nowrap px-3 py-3">
                            <select
                                v-model="item.vat_type_id"
                                class="block w-full rounded-md border-gray-300 py-1.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">—</option>
                                <option
                                    v-for="v in vatTypesList"
                                    :key="v.id"
                                    :value="v.id"
                                >
                                    {{ v.code }}
                                </option>
                            </select>
                        </td>
                        <td
                            class="whitespace-nowrap px-3 py-3 text-right text-sm tabular-nums text-gray-700"
                        >
                            {{ currencySymbol }} {{ lineTotal(item).toFixed(2) }}
                        </td>
                        <td
                            v-if="vatTypesList.length"
                            class="whitespace-nowrap px-3 py-3 text-right text-sm tabular-nums text-gray-700"
                        >
                            {{ currencySymbol }} {{ lineVatAmount(item).toFixed(2) }}
                        </td>
                        <td
                            class="whitespace-nowrap px-3 py-3 text-right text-sm font-medium tabular-nums text-gray-900"
                        >
                            {{ currencySymbol }} {{ (lineTotal(item) + lineVatAmount(item)).toFixed(2) }}
                        </td>
                        <td
                            class="relative whitespace-nowrap py-3 pl-3 pr-4 text-right sm:pr-6"
                        >
                            <button
                                type="button"
                                @click="removeItem(index)"
                                :disabled="items.length <= minRows"
                                class="text-gray-400 hover:text-red-600 disabled:cursor-not-allowed disabled:opacity-50"
                                title="Remove line"
                            >
                                <span aria-hidden="true">×</span>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Summary: Subtotal excl. VAT, VAT, Total -->
        <div class="mt-6 flex flex-col gap-3 border-t border-gray-200 pt-6 sm:max-w-xs sm:ml-auto">
            <div class="flex items-center justify-between gap-4">
                <label class="text-sm font-medium text-gray-700">Subtotal (excl. VAT)</label>
                <input
                    :value="formatNum(totalWoVat)"
                    type="text"
                    readonly
                    class="w-32 rounded-md border-gray-300 bg-gray-100 py-2 text-right text-sm tabular-nums shadow-sm"
                />
            </div>
            <div class="flex items-center justify-between gap-4">
                <label class="text-sm font-medium text-gray-700">VAT</label>
                <input
                    :value="formatNum(totalVat)"
                    type="text"
                    readonly
                    class="w-32 rounded-md border-gray-300 bg-gray-100 py-2 text-right text-sm tabular-nums shadow-sm"
                />
            </div>
            <div class="flex items-center justify-between gap-4">
                <label class="text-sm font-medium text-gray-700">Total</label>
                <input
                    :value="formatNum(invoiceTotal)"
                    type="text"
                    readonly
                    class="w-32 rounded-md border-gray-300 bg-gray-100 py-2 text-right text-sm font-semibold tabular-nums shadow-sm"
                />
            </div>
        </div>
    </div>
</template>
