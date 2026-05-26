<template>
    <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
        <h3 class="text-lg font-medium text-gray-900">Odberateľ</h3>
        <p class="mt-1 text-sm text-gray-600">
            {{ description }}
        </p>

        <div class="mt-6">
            <label for="invoice-recipient-search" class="block text-sm font-medium text-gray-700">
                Vyberte odberateľa alebo vytvorte nového
            </label>
            <AutoComplete
                id="invoice-recipient-search"
                v-model="selectedRecipient"
                :suggestions="filteredRecipients"
                option-label="_label"
                placeholder="Začnite písať..."
                class="mt-1 w-full"
                fluid
                @complete="searchRecipients"
                @item-select="onRecipientSelect"
            >
                <template #option="{ option }">
                    <div class="flex flex-col">
                        <span class="font-medium">{{ option._label }}</span>
                        <span v-if="option.street || option.city" class="text-sm text-gray-500">
                            {{ [option.street, option.city].filter(Boolean).join(', ') }}
                        </span>
                    </div>
                </template>
                <template v-if="showAddRecipientFooter" #footer>
                    <Link
                        :href="route('recipients.create') + '?from_invoice=1'"
                        class="block px-3 py-2 text-center text-sm font-medium text-indigo-600 hover:bg-indigo-50 hover:text-indigo-800"
                    >
                        + Pridať nového odberateľa
                    </Link>
                </template>
            </AutoComplete>
        </div>

        <div class="mt-6 border-t border-gray-200 pt-6">
            <RecipientDetailsForm
                mode="invoice"
                :model-value="recipient"
                :errors="errors"
                id-prefix="invoice-recipient"
            />
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import AutoComplete from 'primevue/autocomplete';
import { Link } from '@inertiajs/vue3';
import RecipientDetailsForm from './RecipientDetailsForm.vue';
import {
    recipientDetailsFromRecipient,
    withRecipientLabel,
} from '../Utils/invoiceFormDefaults';

const props = defineProps({
    recipients: {
        type: Array,
        default: () => [],
    },
    preselectedRecipient: {
        type: Object,
        default: null,
    },
    recipientId: {
        type: [Number, String],
        default: null,
    },
    recipient: {
        type: Object,
        required: true,
    },
    description: {
        type: String,
        required: true,
    },
    errors: {
        type: Object,
        default: () => ({}),
    },
});

const emit = defineEmits(['update:recipientId']);

const selectedRecipient = ref(null);
const filteredRecipients = ref([]);
const recipientQuery = ref('');

const recipientsWithLabel = computed(() =>
    (props.recipients ?? []).map((recipient) => withRecipientLabel(recipient))
);

const showAddRecipientFooter = computed(
    () =>
        filteredRecipients.value.length === 0 &&
        (recipientQuery.value.length > 0 || recipientsWithLabel.value.length === 0)
);

function searchRecipients(event) {
    recipientQuery.value = event.query ?? '';
    const query = recipientQuery.value.trim().toLowerCase();

    filteredRecipients.value = query
        ? recipientsWithLabel.value.filter(
            (item) =>
                (item.name || '').toLowerCase().includes(query) ||
                (item.company_name || '').toLowerCase().includes(query)
        )
        : [...recipientsWithLabel.value];
}

function applyRecipient(selected) {
    if (!selected) return;

    Object.assign(props.recipient, recipientDetailsFromRecipient(selected));
    emit('update:recipientId', selected.id ?? null);
}

function onRecipientSelect(event) {
    applyRecipient(event.value);
}

onMounted(() => {
    if (props.recipientId) {
        const preselected = props.preselectedRecipient &&
            Number(props.preselectedRecipient.id) === Number(props.recipientId)
            ? withRecipientLabel(props.preselectedRecipient)
            : null;

        selectedRecipient.value = recipientsWithLabel.value.find(
            (item) => Number(item.id) === Number(props.recipientId)
        ) ?? preselected;
        return;
    }

    if (props.preselectedRecipient) {
        selectedRecipient.value = withRecipientLabel(props.preselectedRecipient);
        applyRecipient(selectedRecipient.value);
    }
});
</script>
