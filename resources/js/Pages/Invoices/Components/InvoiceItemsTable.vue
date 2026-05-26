<template>
    <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-medium text-gray-900">Položky faktúry</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Pridajte jednu alebo viac položiek s názvom, množstvom, jednotkou a cenou.
                </p>
                <InputError v-if="error" class="mt-2" :message="error" />
            </div>
            <button
                type="button"
                @click="addItem"
                class="rounded-md bg-gray-800 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900"
            >
                Pridať položku
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
                            Názov
                        </th>
                        <th
                            scope="col"
                            class="px-3 py-3 text-right text-xs font-medium uppercase tracking-wide text-gray-500"
                        >
                            Množstvo
                        </th>
                        <th
                            scope="col"
                            class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500"
                        >
                            Jednotka
                        </th>
                        <th
                            scope="col"
                            class="px-3 py-3 text-right text-xs font-medium uppercase tracking-wide text-gray-500"
                        >
                            Jednotková cena
                        </th>
                        <th
                            v-if="vatTypesList.length"
                            scope="col"
                            class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500"
                        >
                            Typ DPH
                        </th>
                        <th
                            scope="col"
                            class="px-3 py-3 text-right text-xs font-medium uppercase tracking-wide text-gray-500"
                        >
                            Suma bez DPH
                        </th>
                        <th
                            v-if="vatTypesList.length"
                            scope="col"
                            class="px-3 py-3 text-right text-xs font-medium uppercase tracking-wide text-gray-500"
                        >
                            DPH
                        </th>
                        <th
                            scope="col"
                            class="px-3 py-3 text-right text-xs font-medium uppercase tracking-wide text-gray-500"
                        >
                            Celkom
                        </th>
                        <th scope="col" class="relative py-3 pl-3 pr-4 sm:pr-6">
                            <span class="sr-only">Odstrániť</span>
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
                                    v-for="unit in UNITS"
                                    :key="unit.value"
                                    :value="unit.value"
                                >
                                    {{ unit.label }}
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
                                <option value="">-</option>
                                <option
                                    v-for="vatType in vatTypesList"
                                    :key="vatType.id"
                                    :value="vatType.id"
                                >
                                    {{ formatVatTypeLabel(vatType) }}
                                </option>
                            </select>
                        </td>
                        <td class="whitespace-nowrap px-3 py-3 text-right text-sm tabular-nums text-gray-700">
                            {{ currencySymbol }} {{ lineTotal(item).toFixed(2) }}
                        </td>
                        <td
                            v-if="vatTypesList.length"
                            class="whitespace-nowrap px-3 py-3 text-right text-sm tabular-nums text-gray-700"
                        >
                            {{ currencySymbol }} {{ lineVatAmount(item).toFixed(2) }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-3 text-right text-sm font-medium tabular-nums text-gray-900">
                            {{ currencySymbol }} {{ (lineTotal(item) + lineVatAmount(item)).toFixed(2) }}
                        </td>
                        <td class="relative whitespace-nowrap py-3 pl-3 pr-4 text-right sm:pr-6">
                            <button
                                type="button"
                                @click="removeItem(index)"
                                :disabled="items.length <= minRows"
                                class="text-gray-400 hover:text-red-600 disabled:cursor-not-allowed disabled:opacity-50"
                                title="Odstrániť riadok"
                            >
                                <span aria-hidden="true">×</span>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex flex-col gap-3 border-t border-gray-200 pt-6 sm:max-w-xs sm:ml-auto">
            <div class="flex items-center justify-between gap-4">
                <label class="text-sm font-medium text-gray-700">Medzisúčet (bez DPH)</label>
                <input
                    :value="formatNum(totalWoVat)"
                    type="text"
                    readonly
                    class="w-32 rounded-md border-gray-300 bg-gray-100 py-2 text-right text-sm tabular-nums shadow-sm"
                />
            </div>
            <div class="flex items-center justify-between gap-4">
                <label class="text-sm font-medium text-gray-700">DPH</label>
                <input
                    :value="formatNum(totalVat)"
                    type="text"
                    readonly
                    class="w-32 rounded-md border-gray-300 bg-gray-100 py-2 text-right text-sm tabular-nums shadow-sm"
                />
            </div>
            <div class="flex items-center justify-between gap-4">
                <label class="text-sm font-medium text-gray-700">Celkom</label>
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

<script setup>
import { computed, ref, watch } from 'vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import { formatVatTypeLabel } from '@/utils/vatTypes';

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
    { value: 'pcs', label: 'ks' },
    { value: 'hrs', label: 'hod' },
    { value: 'days', label: 'dni' },
    { value: 'kg', label: 'kg' },
    { value: 'm', label: 'm' },
    { value: 'm²', label: 'm²' },
];

const vatTypesList = computed(() => props.vatTypes ?? []);

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
        ? props.modelValue.map((item) => ({ ...defaultItem(), ...item }))
        : [defaultItem()]
);

watch(
    items,
    () => {
        emit(
            'update:modelValue',
            items.value.map((item) => ({ ...item }))
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
    const quantity = Number.parseFloat(item.quantity) || 0;
    const unitPrice = Number.parseFloat(item.unit_price) || 0;

    return quantity * unitPrice;
}

function lineVatAmount(item) {
    const lineWoVat = lineTotal(item);
    const vatId = item.vat_type_id != null ? Number(item.vat_type_id) : null;
    if (vatId == null) return 0;

    const vatType = vatTypesList.value.find((type) => Number(type.id) === vatId);
    if (!vatType) return 0;

    const code = String(vatType.code || '').toUpperCase();
    if (code === 'MIMO' || code === 'OSVO') return 0;

    const rate = Number.parseFloat(vatType.rate ?? vatType.code) || 0;
    return lineWoVat * (rate / 100);
}

const totalWoVat = computed(() =>
    items.value.reduce((sum, item) => sum + lineTotal(item), 0)
);
const totalVat = computed(() =>
    items.value.reduce((sum, item) => sum + lineVatAmount(item), 0)
);
const invoiceTotal = computed(() => totalWoVat.value + totalVat.value);

function formatNum(value) {
    return Number(value).toFixed(2).replace('.', ',');
}
</script>
