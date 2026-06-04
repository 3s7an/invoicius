<script setup>
import { ref, watch } from 'vue';
import DatePicker from 'primevue/datepicker';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { defaultInvoiceHeader } from '@/Pages/Invoices/Utils/invoiceFormDefaults';
import { prefixedId, toYMD, ymdToDate } from '@/Pages/Invoices/Utils/helpers';

const props = defineProps({
    modelValue: {
        type: Object,
        default: () => ({}),
    },
    currencies: {
        type: Array,
        default: () => [],
    },
    idPrefix: {
        type: String,
        default: 'invoice',
    },
    errors: {
        type: Object,
        default: () => ({}),
    },
});

const emit = defineEmits(['update:modelValue']);

const header = ref({
    ...defaultInvoiceHeader({ currencies: props.currencies }),
    ...props.modelValue,
});

watch(
    () => props.modelValue,
    (val) => {
        if (val && typeof val === 'object') {
            header.value = { ...defaultInvoiceHeader({ currencies: props.currencies }), ...val };
        }
    },
    { deep: true }
);

watch(
    header,
    () => {
        emit('update:modelValue', { ...header.value });
    },
    { deep: true }
);

const id = (name) => prefixedId(props.idPrefix, name);
</script>

<template>
    <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
        <h3 class="text-lg font-medium text-gray-900">Faktúra</h3>
        <p class="mt-1 text-sm text-gray-600">
            Číslo, variabilný symbol a dátumy.
        </p>
        <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-5">
            <div>
                <InputLabel :for="id('number')" value="Číslo faktúry" />
                <TextInput
                    :id="id('number')"
                    v-model="header.number"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="off"
                />
                <InputError class="mt-2" :message="errors.number" />
            </div>
            <div>
                <InputLabel :for="id('variable-symbol')" value="Variabilný symbol" />
                <TextInput
                    :id="id('variable-symbol')"
                    v-model="header.variable_symbol"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="off"
                />
                <InputError class="mt-2" :message="errors.variable_symbol" />
            </div>
            <div>
                <InputLabel :for="id('issue-date')" value="Dátum vystavenia" />
                <DatePicker
                    :inputId="id('issue-date')"
                    :modelValue="ymdToDate(header.issue_date)"
                    showIcon
                    iconDisplay="input"
                    fluid
                    class="mt-1"
                    @update:modelValue="(d) => { header.issue_date = d ? toYMD(d) : '' }"
                />
                <InputError class="mt-2" :message="errors.issue_date" />
            </div>
            <div>
                <InputLabel :for="id('due-date')" value="Dátum splatnosti" />
                <DatePicker
                    :inputId="id('due-date')"
                    :modelValue="ymdToDate(header.due_date)"
                    showIcon
                    iconDisplay="input"
                    fluid
                    class="mt-1"
                    @update:modelValue="(d) => { header.due_date = d ? toYMD(d) : '' }"
                />
                <InputError class="mt-2" :message="errors.due_date" />
            </div>
            <div v-if="currencies.length">
                <InputLabel :for="id('currency_id')" value="Mena" />
                <select
                    :id="id('currency_id')"
                    v-model="header.currency_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                    <option
                        v-for="c in currencies"
                        :key="c.id"
                        :value="c.id"
                    >
                        {{ c.name }} ({{ c.symbol }})
                    </option>
                </select>
                <InputError class="mt-2" :message="errors.currency_id" />
            </div>
        </div>
    </div>
</template>
