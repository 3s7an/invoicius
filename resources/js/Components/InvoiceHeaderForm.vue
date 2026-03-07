<script setup>
import { ref, watch } from 'vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

const toYMD = (d) => (d instanceof Date ? d.toISOString().slice(0, 10) : d);
const today = toYMD(new Date());
const defaultDue = toYMD(new Date(Date.now() + 14 * 24 * 60 * 60 * 1000));

const props = defineProps({
    modelValue: {
        type: Object,
        default: () => {
            const toYMD = (d) => (d instanceof Date ? d.toISOString().slice(0, 10) : d);
            return {
                number: '',
                variable_symbol: '',
                issue_date: toYMD(new Date()),
                due_date: toYMD(new Date(Date.now() + 14 * 24 * 60 * 60 * 1000)),
                currency_id: '',
            };
        },
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

function defaultHeader() {
    return {
        number: '',
        variable_symbol: '',
        issue_date: today,
        due_date: defaultDue,
        currency_id: props.currencies?.[0]?.id ?? '',
    };
}

const header = ref({
    ...defaultHeader(),
    ...props.modelValue,
});

watch(
    () => props.modelValue,
    (val) => {
        if (val && typeof val === 'object') {
            header.value = { ...defaultHeader(), ...val };
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

function id(name) {
    return props.idPrefix ? `${props.idPrefix}-${name}` : name;
}
</script>

<template>
    <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
        <h3 class="text-lg font-medium text-gray-900">Invoice</h3>
        <p class="mt-1 text-sm text-gray-600">
            Number, variable symbol and dates.
        </p>
        <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-5">
            <div>
                <InputLabel :for="id('number')" value="Invoice number" />
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
                <InputLabel :for="id('variable-symbol')" value="Variable symbol" />
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
                <InputLabel :for="id('issue-date')" value="Issue date" />
                <TextInput
                    :id="id('issue-date')"
                    v-model="header.issue_date"
                    type="date"
                    class="mt-1 block w-full"
                />
                <InputError class="mt-2" :message="errors.issue_date" />
            </div>
            <div>
                <InputLabel :for="id('due-date')" value="Due date" />
                <TextInput
                    :id="id('due-date')"
                    v-model="header.due_date"
                    type="date"
                    class="mt-1 block w-full"
                />
                <InputError class="mt-2" :message="errors.due_date" />
            </div>
            <div v-if="currencies.length">
                <InputLabel :for="id('currency_id')" value="Currency" />
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
