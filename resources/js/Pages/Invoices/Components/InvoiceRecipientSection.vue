<template>
    <div :class="card ? 'overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50' : ''">
        <template v-if="card">
            <h3 class="text-lg font-medium text-gray-900">{{ t('invoices.recipient.title') }}</h3>
            <p class="mt-1 text-sm text-gray-600">{{ description }}</p>
        </template>

        <div :class="card ? 'mt-6' : ''">
            <InputLabel for="invoice-recipient-search" :value="t('invoices.recipient.selectClient')" />
            <AutoComplete
                id="invoice-recipient-search"
                v-model="selectedRecipient"
                :suggestions="filteredRecipients"
                option-label="_label"
                :placeholder="t('invoices.recipient.startTyping')"
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
                        class="block px-3 py-2 text-center text-sm font-medium text-emerald-600 hover:bg-emerald-50 hover:text-emerald-800"
                    >
                        {{ t('invoices.recipient.addNewClient') }}
                    </Link>
                </template>
            </AutoComplete>
        </div>

        <div :class="card ? 'mt-6 border-t border-gray-200 pt-6' : 'mt-4'">
            <RecipientForm
                variant="section"
                fields-mode="invoice"
                :model-value="recipient"
                :errors="errors"
                id-prefix="invoice-recipient"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue';
import AutoComplete from 'primevue/autocomplete';
import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import InputLabel from '@/Components/InputLabel.vue';
import RecipientForm from '@/Pages/Recipients/Components/RecipientForm.vue';
import {
    recipientDetailsFromRecipient,
    withRecipientLabel,
    type RecipientSectionData,
} from '../Utils/invoiceFormDefaults';
import type { RecipientResource } from '@/types';

const { t } = useI18n();

type RecipientWithLabel = RecipientResource & { _label: string }

interface Props {
    recipients?: RecipientResource[]
    preselectedRecipient?: RecipientResource | null
    recipientId?: number | string | null
    recipient: RecipientSectionData
    description: string
    errors?: Record<string, string | undefined>
    card?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    recipients: () => [],
    preselectedRecipient: null,
    recipientId: null,
    errors: () => ({}),
    card: true,
});

const emit = defineEmits<{
    'update:recipientId': [value: number | null]
    'update:recipient': [value: RecipientSectionData]
}>();

const selectedRecipient = ref<RecipientWithLabel | null>(null);
const filteredRecipients = ref<RecipientWithLabel[]>([]);
const recipientQuery = ref('');

const recipientsWithLabel = computed(() =>
    (props.recipients ?? []).map((recipient) => withRecipientLabel(recipient))
);

const showAddRecipientFooter = computed(
    () =>
        filteredRecipients.value.length === 0 &&
        (recipientQuery.value.length > 0 || recipientsWithLabel.value.length === 0)
);

function searchRecipients(event: { query?: string }): void {
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

function applyRecipient(selected: RecipientWithLabel | null): void {
    if (!selected) return;

    emit('update:recipient', { ...props.recipient, ...recipientDetailsFromRecipient(selected) });
    emit('update:recipientId', selected.id ?? null);
}

function onRecipientSelect(event: { value: RecipientWithLabel }): void {
    applyRecipient(event.value);
}

watch(
    selectedRecipient,
    (value) => {
        if (!value) {
            emit('update:recipientId', null);
            return;
        }

        if (typeof value === 'object' && value.id != null) {
            applyRecipient(value);
        }
    },
    { flush: 'sync' }
);

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
