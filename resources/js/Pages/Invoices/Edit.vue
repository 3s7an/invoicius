<script setup>
import { reactive, computed, ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import IssuerDetailsForm from '@/Components/IssuerDetailsForm.vue';
import InvoiceSettings from '@/Components/InvoiceSettings.vue';
import RecipientDetailsForm from '@/Components/RecipientDetailsForm.vue';
import InvoiceHeaderForm from '@/Components/InvoiceHeaderForm.vue';
import InvoiceItemsTable from '@/Components/InvoiceItemsTable.vue';
import AutoComplete from 'primevue/autocomplete';
import Button from 'primevue/button';
import { Head, Link, usePage, router } from '@inertiajs/vue3';

const props = defineProps({
    invoice: {
        type: Object,
        required: true,
    },
    recipients: {
        type: Array,
        default: () => [],
    },
    currencies: {
        type: Array,
        default: () => [],
    },
    vat_types: {
        type: Array,
        default: () => [],
    },
    default_currency_id: {
        type: [Number, String],
        default: null,
    },
    invoice_colors: {
        type: Array,
        default: () => [],
    },
});

const user = usePage().props.auth?.user;
const inv = props.invoice;

const invoiceColorsList = computed(() => {
    const list = props.invoice_colors ?? [];
    return Array.isArray(list) ? list : [];
});

const recipientsWithLabel = computed(() =>
    (props.recipients ?? []).map((r) => ({
        ...r,
        _label: (r.company_name || r.name || '').trim() || '—',
    }))
);

const selectedRecipient = ref(null);
const filteredRecipients = ref([]);
const recipientQuery = ref('');

function searchRecipients(event) {
    recipientQuery.value = event.query ?? '';
    const q = (event.query || '').trim().toLowerCase();
    if (!q) {
        filteredRecipients.value = [...recipientsWithLabel.value];
        return;
    }
    filteredRecipients.value = recipientsWithLabel.value.filter(
        (r) =>
            (r.name || '').toLowerCase().includes(q) ||
            (r.company_name || '').toLowerCase().includes(q)
    );
}

function applyRecipient(r) {
    if (!r) return;
    recipient.recipient_name = (r.company_name || r.name || '').trim();
    recipient.recipient_street = r.street ?? '';
    recipient.recipient_street_num = r.street_num ?? '';
    recipient.recipient_city = r.city ?? '';
    recipient.recipient_state = r.state ?? '';
    recipient.recipient_ico = r.ico ?? '';
    recipient.recipient_dic = r.dic ?? '';
    recipient.recipient_ic_dph = r.ic_dph ?? '';
    recipient.recipient_iban = r.iban ?? '';
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
    if (inv.recipient_id) {
        const match = recipientsWithLabel.value.find((r) => r.id === inv.recipient_id);
        if (match) {
            selectedRecipient.value = match;
        }
    }
});

const formatDate = (d) => {
    if (!d) return '';
    return String(d).slice(0, 10);
};

const invoice = reactive({
    number: inv.number ?? '',
    variable_symbol: inv.varsym ?? '',
    issue_date: formatDate(inv.issue_date),
    due_date: formatDate(inv.due_date),
    currency_id: inv.currency_id ?? props.default_currency_id ?? (props.currencies?.[0]?.id ?? ''),
    invoice_color_id: user?.invoice_color_id ?? (props.invoice_colors?.[0]?.id ?? ''),
});

const currencySymbol = computed(() => {
    const id = Number(invoice.currency_id);
    const c = (props.currencies ?? []).find((x) => Number(x.id) === id);
    return c?.symbol ?? '';
});

const hasUserBillingDetails = computed(() => {
    if (!user) return false;
    return [user.street, user.street_num, user.city, user.zip, user.state, user.ico, user.dic, user.ic_dph].some(
        (v) => v != null && String(v).trim() !== ''
    );
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
    recipient_name: inv.recipient_name ?? '',
    recipient_street: inv.recipient_street ?? '',
    recipient_street_num: inv.recipient_street_num ?? '',
    recipient_city: inv.recipient_city ?? '',
    recipient_state: inv.recipient_state ?? '',
    recipient_ico: inv.recipient_ico ?? '',
    recipient_dic: inv.recipient_dic ?? '',
    recipient_ic_dph: inv.recipient_ic_dph ?? '',
    recipient_iban: inv.iban ?? '',
});

const items = ref(
    (inv.items && inv.items.length)
        ? inv.items.map((item) => ({
            name: item.name ?? '',
            quantity: item.quantity ?? 1,
            unit: item.unit ?? 'pcs',
            unit_price: item.unit_price ?? '',
            vat_type_id: item.vat_type_id ?? null,
        }))
        : [{ name: '', quantity: 1, unit: 'pcs', unit_price: '' }]
);

const hasValidItem = (item) =>
    String(item?.name ?? '').trim() !== '' &&
    Number(Number.parseFloat(item?.quantity) || 0) > 0 &&
    Number(Number.parseFloat(item?.unit_price) || 0) >= 0;

const hasAtLeastOneItem = computed(() =>
    items.value.some((item) => hasValidItem(item))
);

const validationErrors = ref({});

function validateForm() {
    const err = {};
    if (String(invoice.number ?? '').trim() === '') err.number = 'Invoice number is required.';
    if (String(invoice.number ?? '').length > 50) err.number = 'Invoice number must be at most 50 characters.';
    if (String(invoice.variable_symbol ?? '').trim() === '') err.variable_symbol = 'Variable symbol is required.';
    if (String(invoice.variable_symbol ?? '').length > 50) err.variable_symbol = 'Variable symbol must be at most 50 characters.';
    if (String(invoice.issue_date ?? '').trim() === '') err.issue_date = 'Issue date is required.';
    if (String(invoice.due_date ?? '').trim() === '') err.due_date = 'Due date is required.';
    if (invoice.issue_date && invoice.due_date && invoice.due_date < invoice.issue_date) {
        err.due_date = 'Due date must be on or after the issue date.';
    }
    if (!invoice.currency_id) err.currency_id = 'Currency is required.';
    if (String(issuer.name ?? '').trim() === '') err.issuer_name = 'Issuer name is required.';
    if (String(recipient.recipient_name ?? '').trim() === '') err.recipient_name = 'Recipient is required.';
    if (!hasAtLeastOneItem.value) err.items = 'Add at least one item with name, quantity and unit price.';
    return err;
}

const saving = ref(false);

function updateInvoice() {
    const err = validateForm();
    validationErrors.value = err;
    const hasErrors = Object.keys(err).length > 0;
    if (hasErrors || saving.value) return;
    saving.value = true;
    validationErrors.value = {};
    router.put(route('invoices.update', inv.id), {
        number: invoice.number,
        variable_symbol: invoice.variable_symbol,
        issue_date: invoice.issue_date,
        due_date: invoice.due_date,
        currency_id: invoice.currency_id,
        recipient_id: selectedRecipient.value?.id ?? inv.recipient_id ?? null,
        issuer: { ...issuer },
        recipient: { ...recipient },
        items: items.value.filter((item) => hasValidItem(item)),
    }, {
        preserveScroll: true,
        onFinish: () => { saving.value = false; },
    });
}
</script>

<template>
    <Head :title="'Edit Invoice ' + (inv?.number ?? '')" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h1 class="text-lg font-medium text-gray-900">Edit invoice {{ inv?.number }}</h1>
                <Link :href="route('invoices')">
                    <Button label="Back to invoices" icon="pi pi-arrow-left" class="p-button-raised p-button-sm" />
                </Link>
            </div>

            <!-- Invoice header -->
            <InvoiceHeaderForm
                :model-value="invoice"
                :currencies="currencies"
                :errors="validationErrors"
                @update:model-value="(v) => Object.assign(invoice, v)"
                id-prefix="invoice"
            />

            <!-- Invoice settings (logo + color) -->
            <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                <h3 class="text-lg font-medium text-gray-900">Invoice settings</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Company logo and invoice color for this invoice.
                </p>
                <div class="mt-6">
                    <InvoiceSettings
                        mode="invoice"
                        :model="invoice"
                        :user="user"
                        :invoice-colors="invoiceColorsList"
                        id-prefix="invoice-settings"
                    />
                </div>
            </div>

            <!-- Billing details (issuer) -->
            <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                <IssuerDetailsForm
                    :model-value="issuer"
                    :readonly="hasUserBillingDetails"
                    :errors="validationErrors"
                    id-prefix="invoice-issuer"
                />
            </div>

            <!-- Recipient -->
            <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                <h3 class="text-lg font-medium text-gray-900">Recipient</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Search and select a recipient, or edit the details below.
                </p>
                <div class="mt-6">
                    <label for="invoice-recipient-search" class="block text-sm font-medium text-gray-700">
                        Search recipient
                    </label>
                    <AutoComplete
                        id="invoice-recipient-search"
                        v-model="selectedRecipient"
                        :suggestions="filteredRecipients"
                        option-label="_label"
                        placeholder="Type to search..."
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
                                + Add new recipient
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

            <!-- Invoice items -->
            <InvoiceItemsTable
                v-model="items"
                :currency-symbol="currencySymbol"
                :vat-types="vat_types"
                :error="validationErrors.items"
            />

            <!-- Update invoice CTA -->
            <div class="flex justify-end">
                <button
                    type="button"
                    :disabled="saving"
                    @click="updateInvoice"
                    class="w-full min-w-[200px] rounded-lg bg-gray-800 px-6 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900 disabled:cursor-not-allowed disabled:opacity-50 sm:w-auto"
                >
                    {{ saving ? 'Saving…' : 'Update invoice' }}
                </button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
