<template>
    <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h1 class="text-lg font-medium text-gray-900">{{ heading }}</h1>
            <Link :href="route('invoices')">
                <Button label="SpĂ¤ĹĄ na faktĂşry" icon="pi pi-arrow-left" class="p-button-raised p-button-sm" />
            </Link>
        </div>

        <form class="space-y-8" @submit.prevent="submit">
            <InvoiceHeaderForm
                :model-value="invoice"
                :currencies="currencies"
                :errors="validationErrors"
                @update:model-value="(value) => Object.assign(invoice, value)"
                id-prefix="invoice"
            />

            <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                <InvoiceSettings
                    mode="invoice"
                    :user="user"
                    id-prefix="invoice-settings"
                />
            </div>

            <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                <IssuerDetailsForm
                    :model-value="issuer"
                    :readonly="hasUserBillingDetails"
                    :errors="validationErrors"
                    id-prefix="invoice-issuer"
                />
            </div>

            <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                <h3 class="text-lg font-medium text-gray-900">OdberateÄľ</h3>
                <p class="mt-1 text-sm text-gray-600">
                    {{ recipientDescription }}
                </p>
                <div class="mt-6">
                    <label for="invoice-recipient-search" class="block text-sm font-medium text-gray-700">
                        HÄľadaĹĄ odberateÄľa
                    </label>
                    <AutoComplete
                        id="invoice-recipient-search"
                        v-model="selectedRecipient"
                        :suggestions="filteredRecipients"
                        option-label="_label"
                        placeholder="ZaÄŤnite pĂ­saĹĄâ€¦"
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
                                + PridaĹĄ novĂ©ho odberateÄľa
                            </Link>
                        </template>
                    </AutoComplete>
                </div>
                <div class="mt-6 border-t border-gray-200 pt-6">
                    <RecipientDetailsForm
                        mode="invoice"
                        :model-value="recipient"
                        :errors="validationErrors"
                        id-prefix="invoice-recipient"
                    />
                </div>
            </div>

            <InvoiceItemsTable
                v-model="items"
                :currency-symbol="currencySymbol"
                :vat-types="vatTypes"
                :error="validationErrors.items"
            />

            <div class="flex justify-end">
                <button
                    type="submit"
                    :disabled="processing"
                    class="w-full min-w-[200px] rounded-lg bg-gray-800 px-6 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900 disabled:cursor-not-allowed disabled:opacity-50 sm:w-auto"
                >
                    {{ processing ? processingLabel : submitLabel }}
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import AutoComplete from 'primevue/autocomplete';
import Button from 'primevue/button';
import InvoiceHeaderForm from './InvoiceHeaderForm.vue';
import InvoiceItemsTable from './InvoiceItemsTable.vue';
import InvoiceSettings from './InvoiceSettings.vue';
import IssuerDetailsForm from './IssuerDetailsForm.vue';
import RecipientDetailsForm from './RecipientDetailsForm.vue';
import { Link, router, usePage } from '@inertiajs/vue3';

const props = defineProps({
    mode: {
        type: String,
        default: 'create',
        validator: (value) => ['create', 'edit'].includes(value),
    },
    invoice: {
        type: Object,
        default: null,
    },
    recipients: {
        type: Array,
        default: () => [],
    },
    suggestedNumber: {
        type: String,
        default: '',
    },
    preselectedRecipient: {
        type: Object,
        default: null,
    },
    currencies: {
        type: Array,
        default: () => [],
    },
    vatTypes: {
        type: Array,
        default: () => [],
    },
    defaultCurrencyId: {
        type: [Number, String],
        default: null,
    },
});

const user = usePage().props.auth?.user;
const isEdit = computed(() => props.mode === 'edit');
const sourceInvoice = computed(() => props.invoice ?? {});

const toYMD = (date) => date.toISOString().slice(0, 10);
const formatDate = (date) => (date ? String(date).slice(0, 10) : '');
const today = toYMD(new Date());
const defaultDue = toYMD(new Date(Date.now() + 14 * 24 * 60 * 60 * 1000));

const recipientsWithLabel = computed(() =>
    (props.recipients ?? []).map((recipient) => ({
        ...recipient,
        _label: (recipient.company_name || recipient.name || '').trim() || 'â€”',
    }))
);

const selectedRecipient = ref(null);
const filteredRecipients = ref([]);
const recipientQuery = ref('');

const invoice = reactive({
    number: isEdit.value ? (sourceInvoice.value.number ?? '') : (props.suggestedNumber || today),
    variable_symbol: isEdit.value ? (sourceInvoice.value.varsym ?? '') : (props.suggestedNumber || today),
    issue_date: isEdit.value ? formatDate(sourceInvoice.value.issue_date) : today,
    due_date: isEdit.value ? formatDate(sourceInvoice.value.due_date) : defaultDue,
    currency_id: sourceInvoice.value.currency_id ?? props.defaultCurrencyId ?? (props.currencies?.[0]?.id ?? ''),
});

const issuer = reactive({
    name: user?.name ?? '',
    street: user?.street ?? '',
    street_num: user?.street_num ?? '',
    city: user?.city ?? '',
    zip: user?.zip ?? '',
    state: user?.state ?? '',
    ico: user?.ico ?? '',
    dic: user?.dic ?? '',
    ic_dph: user?.ic_dph ?? '',
});

const recipient = reactive({
    recipient_name: sourceInvoice.value.recipient_name ?? '',
    recipient_street: sourceInvoice.value.recipient_street ?? '',
    recipient_street_num: sourceInvoice.value.recipient_street_num ?? '',
    recipient_city: sourceInvoice.value.recipient_city ?? '',
    recipient_state: sourceInvoice.value.recipient_state ?? '',
    recipient_ico: sourceInvoice.value.recipient_ico ?? '',
    recipient_dic: sourceInvoice.value.recipient_dic ?? '',
    recipient_ic_dph: sourceInvoice.value.recipient_ic_dph ?? '',
    recipient_iban: sourceInvoice.value.iban ?? '',
});

function defaultItem() {
    return {
        name: '',
        quantity: 1,
        unit: 'pcs',
        unit_price: '',
        vat_type_id: props.vatTypes?.[0]?.id ?? null,
    };
}

const items = ref(
    isEdit.value && sourceInvoice.value.items?.length
        ? sourceInvoice.value.items.map((item) => ({
            ...defaultItem(),
            name: item.name ?? '',
            quantity: item.quantity ?? 1,
            unit: item.unit ?? 'pcs',
            unit_price: item.unit_price ?? '',
            vat_type_id: item.vat_type_id ?? null,
        }))
        : [defaultItem()]
);

const currencySymbol = computed(() => {
    const id = Number(invoice.currency_id);
    const currency = (props.currencies ?? []).find((item) => Number(item.id) === id);

    return currency?.symbol ?? '';
});

const hasUserBillingDetails = computed(() => {
    if (!user) return false;

    return [user.street, user.street_num, user.city, user.zip, user.state, user.ico, user.dic, user.ic_dph].some(
        (value) => value != null && String(value).trim() !== ''
    );
});

const hasValidItem = (item) =>
    String(item?.name ?? '').trim() !== '' &&
    Number(Number.parseFloat(item?.quantity) || 0) > 0 &&
    Number(Number.parseFloat(item?.unit_price) || 0) >= 0;

const hasAtLeastOneItem = computed(() =>
    items.value.some((item) => hasValidItem(item))
);

const validationErrors = ref({});
const processing = ref(false);

const heading = computed(() =>
    isEdit.value
        ? `UpraviĹĄ faktĂşru ${sourceInvoice.value?.number ?? ''}`
        : 'NovĂˇ faktĂşra'
);
const recipientDescription = computed(() =>
    isEdit.value
        ? 'VyhÄľadajte a vyberte odberateÄľa alebo upravte Ăşdaje niĹľĹˇie.'
        : 'VyhÄľadajte a vyberte odberateÄľa alebo pridajte novĂ©ho.'
);
const submitLabel = computed(() => (isEdit.value ? 'UloĹľiĹĄ faktĂşru' : 'VytvoriĹĄ faktĂşru'));
const processingLabel = computed(() => (isEdit.value ? 'UkladĂˇmâ€¦' : 'VytvĂˇramâ€¦'));

function searchRecipients(event) {
    recipientQuery.value = event.query ?? '';
    const query = recipientQuery.value.trim().toLowerCase();

    if (!query) {
        filteredRecipients.value = [...recipientsWithLabel.value];
        return;
    }

    filteredRecipients.value = recipientsWithLabel.value.filter(
        (item) =>
            (item.name || '').toLowerCase().includes(query) ||
            (item.company_name || '').toLowerCase().includes(query)
    );
}

function applyRecipient(selected) {
    if (!selected) return;

    recipient.recipient_name = (selected.company_name || selected.name || '').trim();
    recipient.recipient_street = selected.street ?? '';
    recipient.recipient_street_num = selected.street_num ?? '';
    recipient.recipient_city = selected.city ?? '';
    recipient.recipient_state = selected.state ?? '';
    recipient.recipient_ico = selected.ico ?? '';
    recipient.recipient_dic = selected.dic ?? '';
    recipient.recipient_ic_dph = selected.ic_dph ?? '';
    recipient.recipient_iban = selected.iban ?? '';
}

function onRecipientSelect(event) {
    applyRecipient(event.value);
}

const showAddRecipientFooter = computed(
    () =>
        filteredRecipients.value.length === 0 &&
        (recipientQuery.value.length > 0 || recipientsWithLabel.value.length === 0)
);

onMounted(() => {
    if (isEdit.value && sourceInvoice.value.recipient_id) {
        selectedRecipient.value = recipientsWithLabel.value.find(
            (item) => Number(item.id) === Number(sourceInvoice.value.recipient_id)
        ) ?? null;
        return;
    }

    if (props.preselectedRecipient) {
        const withLabel = {
            ...props.preselectedRecipient,
            _label: (props.preselectedRecipient.company_name || props.preselectedRecipient.name || '').trim() || 'â€”',
        };

        selectedRecipient.value = withLabel;
        applyRecipient(withLabel);
    }
});

function invoiceItemsForSubmit() {
    return items.value
        .filter((item) => hasValidItem(item))
        .map((item) => ({
            ...item,
            vat_type_id: item.vat_type_id === '' || item.vat_type_id == null ? null : item.vat_type_id,
        }));
}

function payload() {
    return {
        number: invoice.number,
        variable_symbol: invoice.variable_symbol,
        issue_date: invoice.issue_date,
        due_date: invoice.due_date,
        currency_id: invoice.currency_id,
        recipient_id: selectedRecipient.value?.id ?? sourceInvoice.value.recipient_id ?? null,
        issuer: { ...issuer },
        recipient: { ...recipient },
        items: invoiceItemsForSubmit(),
    };
}

function validateForm() {
    const errors = {};

    if (String(invoice.number ?? '').trim() === '') errors.number = 'ÄŚĂ­slo faktĂşry je povinnĂ©.';
    if (String(invoice.number ?? '').length > 50) errors.number = 'ÄŚĂ­slo faktĂşry mĂ´Ĺľe maĹĄ najviac 50 znakov.';
    if (String(invoice.variable_symbol ?? '').trim() === '') errors.variable_symbol = 'VariabilnĂ˝ symbol je povinnĂ˝.';
    if (String(invoice.variable_symbol ?? '').length > 50) {
        errors.variable_symbol = 'VariabilnĂ˝ symbol mĂ´Ĺľe maĹĄ najviac 50 znakov.';
    }
    if (String(invoice.issue_date ?? '').trim() === '') errors.issue_date = 'DĂˇtum vystavenia je povinnĂ˝.';
    if (String(invoice.due_date ?? '').trim() === '') errors.due_date = 'DĂˇtum splatnosti je povinnĂ˝.';
    if (invoice.issue_date && invoice.due_date && invoice.due_date < invoice.issue_date) {
        errors.due_date = 'DĂˇtum splatnosti musĂ­ byĹĄ v deĹ vystavenia alebo po Ĺom.';
    }
    if (!invoice.currency_id) errors.currency_id = 'Mena je povinnĂˇ.';
    if (String(issuer.name ?? '').trim() === '') errors.issuer_name = 'Meno alebo firma vystaviteÄľa je povinnĂ©.';
    if (String(recipient.recipient_name ?? '').trim() === '') errors.recipient_name = 'OdberateÄľ je povinnĂ˝.';
    if (!hasAtLeastOneItem.value) {
        errors.items = 'Pridajte aspoĹ jednu poloĹľku s nĂˇzvom, mnoĹľstvom a jednotkovou cenou.';
    }

    return errors;
}

function submit() {
    const errors = validateForm();
    validationErrors.value = errors;

    if (Object.keys(errors).length > 0 || processing.value) return;

    processing.value = true;
    validationErrors.value = {};

    const options = {
        preserveScroll: true,
        onFinish: () => {
            processing.value = false;
        },
    };

    if (isEdit.value) {
        router.put(route('invoices.update', sourceInvoice.value.id), payload(), options);
        return;
    }

    router.post(route('invoices.store'), payload(), options);
}
</script>
