<script setup lang="ts">
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import DatePicker from 'primevue/datepicker';
import AppDrawer from '@/Components/AppDrawer.vue';
import AppSelect from '@/Components/AppSelect.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InvoiceItemsTable from './InvoiceItemsTable.vue';
import InvoiceRecipientSection from './InvoiceRecipientSection.vue';
import { useInvoiceForm } from '../Composables/useInvoiceForm';
import { invoicePayload } from '../Utils/invoiceFormDefaults';
import { currencyName, toYMD, ymdToDate } from '../Utils/helpers';
import type { AuthUser } from '@/types/inertia';
import type { InvoiceResource, RecipientResource, CurrencyResource, VatTypeResource } from '@/types';

const { t } = useI18n();

interface Props {
    show: boolean
    invoice?: InvoiceResource | null
    recipients?: RecipientResource[]
    suggestedNumber?: string
    currencies?: CurrencyResource[]
    vatTypes?: VatTypeResource[]
    defaultCurrencyId?: number | null
}

const props = withDefaults(defineProps<Props>(), {
    invoice: null,
    recipients: () => [],
    suggestedNumber: '',
    currencies: () => [],
    vatTypes: () => [],
    defaultCurrencyId: null,
});

const emit = defineEmits<{ close: [] }>();

const user = (usePage().props as { auth?: { user: AuthUser } }).auth?.user;
const isEdit = computed(() => props.invoice != null);

const {
    form,
    errors,
    heading,
    recipientDescription,
    submitLabel,
    processingLabel,
    currencySymbol,
} = useInvoiceForm(
    {
        mode: isEdit.value ? 'edit' : 'create',
        invoice: props.invoice,
        recipients: props.recipients,
        suggestedNumber: props.suggestedNumber,
        currencies: props.currencies,
        vatTypes: props.vatTypes,
        defaultCurrencyId: props.defaultCurrencyId,
    },
    user,
);

const currencyOptions = computed(() =>
    (props.currencies ?? []).map((c) => ({ value: c.id, label: `${currencyName(c.symbol, c.name)} (${c.symbol})` })),
);

function submitDrawer(): void {
    const request = form.transform(invoicePayload);
    if (isEdit.value && props.invoice) {
        request.put(route('invoices.update', props.invoice.id), {
            preserveScroll: true,
            onSuccess: () => emit('close'),
        });
    } else {
        request.post(route('invoices.store'), {
            preserveScroll: true,
            onSuccess: () => emit('close'),
        });
    }
}
</script>

<template>
    <AppDrawer
        :show="show"
        :title="heading"
        :badge="!isEdit ? form.number : undefined"
        @close="emit('close')"
    >
        <form @submit.prevent="submitDrawer">
            <!-- ── Základné údaje ─── -->
            <div class="flex items-center gap-2.5 mb-4 mt-5">
                <span class="text-[11px] font-semibold uppercase tracking-[0.08em] text-gray-400 whitespace-nowrap">{{ t('invoices.sections.basicData') }}</span>
                <span class="flex-1 h-px bg-gray-200" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <InputLabel for="drawer-number" :value="t('invoices.header.number')" />
                    <TextInput
                        id="drawer-number"
                        v-model="form.number"
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="off"
                    />
                    <InputError class="mt-1" :message="errors.number" />
                </div>

                <div>
                    <InputLabel for="drawer-vs" :value="t('invoices.header.variableSymbol')" />
                    <TextInput
                        id="drawer-vs"
                        v-model="form.variable_symbol"
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="off"
                    />
                    <InputError class="mt-1" :message="errors.variable_symbol" />
                </div>

                <div>
                    <InputLabel for="drawer-issue-date" :value="t('invoices.header.issueDate')" />
                    <DatePicker
                        inputId="drawer-issue-date"
                        :modelValue="ymdToDate(form.issue_date)"
                        showIcon
                        iconDisplay="input"
                        fluid
                        class="mt-1"
                        @update:model-value="(d: unknown) => { Object.assign(form, { issue_date: (d instanceof Date) ? toYMD(d) : '' }) }"
                    />
                    <InputError class="mt-1" :message="errors.issue_date" />
                </div>

                <div>
                    <InputLabel for="drawer-due-date" :value="t('invoices.header.dueDate')" />
                    <DatePicker
                        inputId="drawer-due-date"
                        :modelValue="ymdToDate(form.due_date)"
                        showIcon
                        iconDisplay="input"
                        fluid
                        class="mt-1"
                        @update:model-value="(d: unknown) => { Object.assign(form, { due_date: (d instanceof Date) ? toYMD(d) : '' }) }"
                    />
                    <InputError class="mt-1" :message="errors.due_date" />
                </div>

                <div class="col-span-2">
                    <InputLabel for="drawer-currency" :value="t('invoices.header.currency')" />
                    <AppSelect
                        inputId="drawer-currency"
                        v-model="form.currency_id"
                        :options="currencyOptions"
                        class="mt-1"
                    />
                    <InputError class="mt-1" :message="errors.currency_id" />
                </div>
            </div>

            <!-- ── Klient ─── -->
            <div class="flex items-center gap-2.5 mb-4 mt-6">
                <span class="text-[11px] font-semibold uppercase tracking-[0.08em] text-gray-400 whitespace-nowrap">{{ t('invoices.sections.client') }}</span>
                <span class="flex-1 h-px bg-gray-200" />
            </div>

            <InvoiceRecipientSection
                v-model:recipient-id="form.recipient_id"
                v-model:recipient="form.recipient"
                :recipients="recipients"
                :description="recipientDescription"
                :errors="errors"
                :card="false"
            />

            <!-- ── Položky faktúry ─── -->
            <div class="flex items-center gap-2.5 mb-4 mt-6">
                <span class="text-[11px] font-semibold uppercase tracking-[0.08em] text-gray-400 whitespace-nowrap">{{ t('invoices.sections.items') }}</span>
                <span class="flex-1 h-px bg-gray-200" />
            </div>

            <InvoiceItemsTable
                v-model="form.items"
                :currency-symbol="currencySymbol"
                :vat-types="vatTypes"
                :error="errors.items"
                :card="false"
            />
        </form>

        <template #footer>
            <div class="flex justify-end gap-2">
                <SecondaryButton type="button" @click="emit('close')">{{ t('common.cancel') }}</SecondaryButton>
                <PrimaryButton type="button" :disabled="form.processing" @click="submitDrawer">
                    {{ form.processing ? processingLabel : submitLabel }}
                </PrimaryButton>
            </div>
        </template>
    </AppDrawer>
</template>
