<template>
    <!-- ── Card mode (full-page form) ── -->
    <div v-if="card" class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-medium text-gray-900">{{ t('invoices.items.title') }}</h3>
                <p class="mt-1 text-sm text-gray-600">
                    {{ t('invoices.items.description') }}
                </p>
                <InputError v-if="error" class="mt-2" :message="error" />
            </div>
            <button
                type="button"
                @click="addItem"
                class="rounded-md bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 focus:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 active:bg-emerald-800"
            >
                {{ t('invoices.items.addItem') }}
            </button>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="py-3 pl-4 pr-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:pl-6">{{ t('invoices.items.name') }}</th>
                        <th scope="col" class="px-3 py-3 text-right text-xs font-medium uppercase tracking-wide text-gray-500">{{ t('invoices.items.quantity') }}</th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">{{ t('invoices.items.unit') }}</th>
                        <th scope="col" class="px-3 py-3 text-right text-xs font-medium uppercase tracking-wide text-gray-500">{{ t('invoices.items.unitPrice') }}</th>
                        <th v-if="vatTypesList.length" scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">{{ t('invoices.items.vatType') }}</th>
                        <th scope="col" class="px-3 py-3 text-right text-xs font-medium uppercase tracking-wide text-gray-500">{{ t('invoices.items.amountExclVat') }}</th>
                        <th v-if="vatTypesList.length" scope="col" class="px-3 py-3 text-right text-xs font-medium uppercase tracking-wide text-gray-500">{{ t('invoices.items.vat') }}</th>
                        <th scope="col" class="px-3 py-3 text-right text-xs font-medium uppercase tracking-wide text-gray-500">{{ t('invoices.items.total') }}</th>
                        <th scope="col" class="relative py-3 pl-3 pr-4 sm:pr-6"><span class="sr-only">{{ t('invoices.items.remove') }}</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    <tr v-for="(item, index) in items" :key="index">
                        <td class="whitespace-nowrap py-3 pl-4 pr-3 sm:pl-6">
                            <TextInput v-model="item.name" type="text" class="block w-full min-w-[200px]" />
                        </td>
                        <td class="whitespace-nowrap px-3 py-3">
                            <TextInput v-model="item.quantity" type="number" min="0" step="any" class="block w-full text-right" />
                        </td>
                        <td class="whitespace-nowrap px-3 py-3">
                            <AppSelect v-model="item.unit" :options="unitOptions" size="small" />
                        </td>
                        <td class="whitespace-nowrap px-3 py-3">
                            <TextInput v-model="item.unit_price" type="number" min="0" step="0.01" class="block w-full text-right" />
                        </td>
                        <td v-if="vatTypesList.length" class="whitespace-nowrap px-3 py-3">
                            <AppSelect v-model="item.vat_type_id" :options="vatTypeOptions" placeholder="-" show-clear size="small" />
                        </td>
                        <td class="whitespace-nowrap px-3 py-3 text-right text-sm tabular-nums text-gray-700">
                            {{ currencySymbol }} {{ lineTotal(item).toFixed(2) }}
                        </td>
                        <td v-if="vatTypesList.length" class="whitespace-nowrap px-3 py-3 text-right text-sm tabular-nums text-gray-700">
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
                                :title="t('invoices.items.removeRow')"
                            >
                                ×
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex flex-col gap-3 border-t border-gray-200 pt-6 sm:max-w-xs sm:ml-auto">
            <div class="flex items-center justify-between gap-4">
                <label class="text-sm font-medium text-gray-700">{{ t('invoices.items.subtotal') }}</label>
                <input :value="fmt(totalWoVat)" type="text" readonly class="w-32 rounded-md border-gray-300 bg-gray-100 py-2 text-right text-sm tabular-nums shadow-sm">
            </div>
            <div class="flex items-center justify-between gap-4">
                <label class="text-sm font-medium text-gray-700">{{ t('invoices.items.vat') }}</label>
                <input :value="fmt(totalVat)" type="text" readonly class="w-32 rounded-md border-gray-300 bg-gray-100 py-2 text-right text-sm tabular-nums shadow-sm">
            </div>
            <div class="flex items-center justify-between gap-4">
                <label class="text-sm font-medium text-gray-700">{{ t('invoices.items.total') }}</label>
                <input :value="fmt(invoiceTotal)" type="text" readonly class="w-32 rounded-md border-gray-300 bg-gray-100 py-2 text-right text-sm font-semibold tabular-nums shadow-sm">
            </div>
        </div>
    </div>

    <!-- ── Flat / drawer mode (card=false) ── -->
    <div v-else>
        <InputError v-if="error" class="mb-3" :message="error" />

        <!-- ── Desktop grid (sm+) ── -->
        <div class="hidden sm:block">
            <!-- Column headers -->
            <div class="grid mb-2" :style="gridTemplateColumns" style="gap:8px">
                <span class="flat-th">{{ t('invoices.items.flatDescription') }}</span>
                <span class="flat-th">{{ t('invoices.items.qtyShort') }}</span>
                <span class="flat-th">{{ t('invoices.items.unitShort') }}</span>
                <span class="flat-th text-right">{{ t('invoices.items.pricePerUnit') }}</span>
                <span v-if="vatTypesList.length" class="flat-th text-right">{{ t('invoices.items.vat') }}</span>
                <span class="flat-th text-right">{{ t('invoices.items.totalShort') }}</span>
                <span />
            </div>

            <!-- Item rows -->
            <div
                v-for="(item, index) in items"
                :key="index"
                class="grid"
                :style="gridTemplateColumns"
                style="gap:8px; margin-bottom:10px; align-items:start"
            >
                <TextInput v-model="item.name" type="text" :placeholder="t('invoices.items.flatDescription')" class="block w-full" />
                <TextInput v-model="item.quantity" type="number" min="0" step="any" class="block w-full" />
                <div class="relative">
                    <select v-model="item.unit" class="flat-select">
                        <option v-for="opt in unitOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
                    </select>
                    <i class="pi pi-chevron-down flat-select-chevron" aria-hidden="true" />
                </div>
                <TextInput v-model="item.unit_price" type="number" min="0" step="0.01" class="block w-full" />
                <div v-if="vatTypesList.length" class="relative">
                    <select
                        :value="item.vat_type_id ?? ''"
                        class="flat-select"
                        @change="(e) => { const v = (e.target as HTMLSelectElement).value; item.vat_type_id = v ? Number(v) : null }"
                    >
                        <option value="">–</option>
                        <option v-for="opt in vatTypeOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
                    </select>
                    <i class="pi pi-chevron-down flat-select-chevron" aria-hidden="true" />
                </div>
                <div style="padding-top:9px; text-align:right; font-size:14px; font-weight:600; color:var(--text-strong); font-variant-numeric:tabular-nums; white-space:nowrap">
                    {{ fmt(lineTotal(item) + lineVatAmount(item)) }} {{ currencySymbol }}
                </div>
                <div style="padding-top:4px">
                    <button
                        v-if="items.length > minRows"
                        type="button"
                        @click="removeItem(index)"
                        class="flex h-8 w-8 items-center justify-center rounded text-gray-400 transition hover:bg-red-50 hover:text-red-500"
                        :title="t('invoices.items.remove')"
                    >
                        <i class="pi pi-trash text-[13px]" aria-hidden="true" />
                    </button>
                    <div v-else class="h-8 w-8" />
                </div>
            </div>
        </div>

        <!-- ── Mobile cards (< sm) ── -->
        <div class="sm:hidden space-y-2.5">
            <div
                v-for="(item, index) in items"
                :key="index"
                class="rounded-md border border-gray-200 p-3 space-y-2"
            >
                <!-- Name + trash -->
                <div class="flex items-center gap-2">
                    <TextInput v-model="item.name" type="text" :placeholder="t('invoices.items.flatDescription')" class="block flex-1" />
                    <button
                        v-if="items.length > minRows"
                        type="button"
                        @click="removeItem(index)"
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded text-gray-400 hover:bg-red-50 hover:text-red-500"
                    >
                        <i class="pi pi-trash text-[13px]" aria-hidden="true" />
                    </button>
                </div>

                <!-- Mn | Jednotka | Cena/j. | DPH -->
                <div
                    class="grid gap-2"
                    :class="vatTypesList.length ? 'grid-cols-4' : 'grid-cols-3'"
                >
                    <div>
                        <span class="flat-th">{{ t('invoices.items.qtyShort') }}</span>
                        <TextInput v-model="item.quantity" type="number" min="0" step="any" class="block w-full" />
                    </div>
                    <div>
                        <span class="flat-th">{{ t('invoices.items.unitShortMobile') }}</span>
                        <div class="relative">
                            <select v-model="item.unit" class="flat-select">
                                <option v-for="opt in unitOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
                            </select>
                            <i class="pi pi-chevron-down flat-select-chevron" aria-hidden="true" />
                        </div>
                    </div>
                    <div>
                        <span class="flat-th">{{ t('invoices.items.pricePerUnit') }}</span>
                        <TextInput v-model="item.unit_price" type="number" min="0" step="0.01" class="block w-full" />
                    </div>
                    <div v-if="vatTypesList.length">
                        <span class="flat-th">{{ t('invoices.items.vat') }}</span>
                        <div class="relative">
                            <select
                                :value="item.vat_type_id ?? ''"
                                class="flat-select"
                                @change="(e) => { const v = (e.target as HTMLSelectElement).value; item.vat_type_id = v ? Number(v) : null }"
                            >
                                <option value="">–</option>
                                <option v-for="opt in vatTypeOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
                            </select>
                            <i class="pi pi-chevron-down flat-select-chevron" aria-hidden="true" />
                        </div>
                    </div>
                </div>

                <!-- Total -->
                <div class="text-right text-sm font-semibold tabular-nums" style="color:var(--text-strong)">
                    {{ fmt(lineTotal(item) + lineVatAmount(item)) }} {{ currencySymbol }}
                </div>
            </div>
        </div>

        <!-- Add item -->
        <button
            type="button"
            @click="addItem"
            class="mt-2 flex items-center gap-1.5 text-sm font-medium text-emerald-600 hover:text-emerald-700"
        >
            <i class="pi pi-plus text-[11px]" aria-hidden="true" />
            {{ t('invoices.items.addItem') }}
        </button>

        <!-- Totals -->
        <div class="mt-6 flex justify-end border-t border-gray-200 pt-[18px]">
            <div class="w-full sm:min-w-[300px] sm:w-auto">
                <div
                    v-for="[label, value] in [[t('invoices.items.vatBase'), totalWoVat], [t('invoices.items.vatLabel'), totalVat]]"
                    :key="label"
                    class="flex justify-between gap-6 mb-2 text-sm text-gray-500"
                >
                    <span>{{ label }}</span>
                    <span class="tabular-nums">{{ fmt(value as number) }} {{ currencySymbol }}</span>
                </div>
                <div class="flex justify-between gap-6 pt-2.5 text-base font-bold text-gray-900" style="border-top:2px solid var(--border-strong)">
                    <span>{{ t('invoices.items.total') }}:</span>
                    <span class="tabular-nums">{{ fmt(invoiceTotal) }} {{ currencySymbol }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import { formatVatTypeLabel } from '@/utils/vatTypes';
import AppSelect from '@/Components/AppSelect.vue';
import { defaultInvoiceItem, type InvoiceItemFormData } from '@/Pages/Invoices/Utils/invoiceFormDefaults';
import { getUnitOptions } from '@/utils/units';
import { usePage } from '@inertiajs/vue3';
import type { AuthUser } from '@/types/inertia';
import type { VatTypeResource } from '@/types';

const { t } = useI18n();

const defaultUserVatTypeId = (usePage().props as { auth?: { user: AuthUser } }).auth?.user?.default_vat_type_id ?? null;

interface Props {
    modelValue?: InvoiceItemFormData[]
    currencySymbol?: string
    vatTypes?: VatTypeResource[]
    minRows?: number
    error?: string
    card?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: () => [],
    currencySymbol: '€',
    vatTypes: () => [],
    minRows: 1,
    error: '',
    card: true,
});

const emit = defineEmits<{
    'update:modelValue': [value: InvoiceItemFormData[]]
}>();

const vatTypesList = computed(() => props.vatTypes ?? []);

const vatTypeOptions = computed(() =>
    vatTypesList.value.map((vatType) => ({
        value: vatType.id,
        label: formatVatTypeLabel(vatType),
    })),
);

const unitOptions = computed(() => getUnitOptions());

const gridTemplateColumns = computed(() =>
    vatTypesList.value.length
        ? 'grid-template-columns: minmax(0,2fr) 72px 96px 118px 84px 104px 36px'
        : 'grid-template-columns: minmax(0,2fr) 72px 96px 118px 104px 36px'
);

function mergeItem(item: InvoiceItemFormData): InvoiceItemFormData {
    const defaults = defaultInvoiceItem(defaultUserVatTypeId, vatTypesList.value);
    const vatTypeId = item.vat_type_id;
    const resolvedVatTypeId =
        vatTypeId != null && vatTypeId !== '' && typeof vatTypeId !== 'object'
            ? vatTypeId
            : defaults.vat_type_id;

    return {
        ...defaults,
        ...item,
        vat_type_id: resolvedVatTypeId,
    };
}

const items = ref<InvoiceItemFormData[]>(
    props.modelValue?.length
        ? props.modelValue.map(mergeItem)
        : [defaultInvoiceItem(defaultUserVatTypeId, vatTypesList.value)]
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

function addItem(): void {
    items.value.push(defaultInvoiceItem(defaultUserVatTypeId, vatTypesList.value));
}

function removeItem(index: number): void {
    if (items.value.length <= props.minRows) return;
    items.value.splice(index, 1);
}

function lineTotal(item: InvoiceItemFormData): number {
    const quantity = Number.parseFloat(String(item.quantity)) || 0;
    const unitPrice = Number.parseFloat(String(item.unit_price)) || 0;

    return quantity * unitPrice;
}

function lineVatAmount(item: InvoiceItemFormData): number {
    const lineWoVat = lineTotal(item);
    const vatId = item.vat_type_id != null ? Number(item.vat_type_id) : null;
    if (vatId == null) return 0;

    const vatType = vatTypesList.value.find((type) => Number(type.id) === vatId);
    if (!vatType) return 0;

    const code = String(vatType.code || '').toUpperCase();
    if (code === 'MIMO' || code === 'OSVO') return 0;

    const rate = Number.parseFloat(String(vatType.rate ?? vatType.code)) || 0;
    return lineWoVat * (rate / 100);
}

const totalWoVat = computed(() =>
    items.value.reduce((sum, item) => sum + lineTotal(item), 0)
);
const totalVat = computed(() =>
    items.value.reduce((sum, item) => sum + lineVatAmount(item), 0)
);
const invoiceTotal = computed(() => totalWoVat.value + totalVat.value);

function fmt(value: number): string {
    return Number(value).toLocaleString('sk-SK', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}
</script>
