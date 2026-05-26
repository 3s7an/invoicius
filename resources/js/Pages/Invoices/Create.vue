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
    recipients: {
        type: Array,
        default: () => [],
    },
    suggested_number: {
        type: String,
        default: '',
    },
    preselected_recipient: {
        type: Object,
        default: null,
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
});

const user = usePage().props.auth?.user;

// Add display label for AutoComplete (company_name || name)
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
    const pre = props.preselected_recipient;
    if (pre) {
        const withLabel = { ...pre, _label: (pre.company_name || pre.name || '').trim() || '—' };
        selectedRecipient.value = withLabel;
        applyRecipient(withLabel);
    }
});

const toYMD = (d) => d.toISOString().slice(0, 10);
const today = toYMD(new Date());
const defaultDue = toYMD(new Date(Date.now() + 14 * 24 * 60 * 60 * 1000));

const invoice = reactive({
    number: props.suggested_number || today,
    variable_symbol: props.suggested_number || today,
    issue_date: today,
    due_date: defaultDue,
    currency_id: props.default_currency_id ?? (props.currencies?.[0]?.id ?? ''),
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
    recipient_name: '',
    recipient_street: '',
    recipient_street_num: '',
    recipient_city: '',
    recipient_state: '',
    recipient_ico: '',
    recipient_dic: '',
    recipient_ic_dph: '',
    recipient_iban: '',
});

const items = ref([
    { name: '', quantity: 1, unit: 'pcs', unit_price: '' },
]);

const hasValidItem = (item) =>
    String(item?.name ?? '').trim() !== '' &&
    Number(Number.parseFloat(item?.quantity) || 0) > 0 &&
    Number(Number.parseFloat(item?.unit_price) || 0) >= 0;

const hasAtLeastOneItem = computed(() =>
    items.value.some((item) => hasValidItem(item))
);

const validationErrors = ref({});

function invoiceItemsForSubmit() {
    return items.value
        .filter((item) => hasValidItem(item))
        .map((item) => ({
            ...item,
            vat_type_id: item.vat_type_id === '' ? null : item.vat_type_id,
        }));
}

function validateForm() {
    const err = {};
    if (String(invoice.number ?? '').trim() === '') err.number = 'Číslo faktúry je povinné.';
    if (String(invoice.number ?? '').length > 50) err.number = 'Číslo faktúry môže mať najviac 50 znakov.';
    if (String(invoice.variable_symbol ?? '').trim() === '') err.variable_symbol = 'Variabilný symbol je povinný.';
    if (String(invoice.variable_symbol ?? '').length > 50) err.variable_symbol = 'Variabilný symbol môže mať najviac 50 znakov.';
    if (String(invoice.issue_date ?? '').trim() === '') err.issue_date = 'Dátum vystavenia je povinný.';
    if (String(invoice.due_date ?? '').trim() === '') err.due_date = 'Dátum splatnosti je povinný.';
    if (invoice.issue_date && invoice.due_date && invoice.due_date < invoice.issue_date) {
        err.due_date = 'Dátum splatnosti musí byť v deň vystavenia alebo po ňom.';
    }
    if (!invoice.currency_id) err.currency_id = 'Mena je povinná.';
    if (String(issuer.name ?? '').trim() === '') err.issuer_name = 'Meno alebo firma vystaviteľa je povinné.';
    if (String(recipient.recipient_name ?? '').trim() === '') err.recipient_name = 'Odberateľ je povinný.';
    if (!hasAtLeastOneItem.value) err.items = 'Pridajte aspoň jednu položku s názvom, množstvom a jednotkovou cenou.';
    return err;
}

const creating = ref(false);

function createInvoice() {
    const err = validateForm();
    validationErrors.value = err;
    const hasErrors = Object.keys(err).length > 0;
    if (hasErrors || creating.value) return;
    creating.value = true;
    validationErrors.value = {};
    router.post(route('invoices.store'), {
        number: invoice.number,
        variable_symbol: invoice.variable_symbol,
        issue_date: invoice.issue_date,
        due_date: invoice.due_date,
        currency_id: invoice.currency_id,
        recipient_id: selectedRecipient.value?.id ?? null,
        issuer: { ...issuer },
        recipient: { ...recipient },
        items: invoiceItemsForSubmit(),
    }, {
        preserveScroll: true,
        onFinish: () => { creating.value = false; },
    });
}
</script>

<template>
    <Head title="Nová faktúra" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h1 class="text-lg font-medium text-gray-900">Nová faktúra</h1>
                <Link :href="route('invoices')">
                    <Button label="Späť na faktúry" icon="pi pi-arrow-left" class="p-button-raised p-button-sm" />
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

                <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                    <InvoiceSettings
                        mode="invoice"
                        :user="user"
                        id-prefix="invoice-settings"
                    />
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
                    <h3 class="text-lg font-medium text-gray-900">Odberateľ</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Vyhľadajte a vyberte odberateľa alebo pridajte nového.
                    </p>
                    <div class="mt-6">
                        <label for="invoice-recipient-search" class="block text-sm font-medium text-gray-700">
                            Hľadať odberateľa
                        </label>
                        <AutoComplete
                            id="invoice-recipient-search"
                            v-model="selectedRecipient"
                            :suggestions="filteredRecipients"
                            option-label="_label"
                            placeholder="Začnite písať…"
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

                <!-- Create invoice CTA -->
                 <div class="flex justify-end">
                    <button
                        type="button"
                        :disabled="creating"
                        @click="createInvoice"
                        class="w-full min-w-[200px] rounded-lg bg-gray-800 px-6 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900 disabled:cursor-not-allowed disabled:opacity-50 sm:w-auto"
                    >
                        {{ creating ? 'Vytváram…' : 'Vytvoriť faktúru' }}
                    </button>
                </div>
        </div>
    </AuthenticatedLayout>
</template>
