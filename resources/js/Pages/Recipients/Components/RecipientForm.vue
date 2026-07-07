<template>
    <div v-if="variant === 'page'" class="space-y-8">
        <PageHeader :title="heading">
            <template #actions>
                <Link :href="route('recipients.index')">
                    <Button :label="t('recipients.backToRecipients')" icon="pi pi-arrow-left" class="p-button-raised p-button-sm" />
                </Link>
            </template>
        </PageHeader>

        <form class="space-y-8" @submit.prevent="submit">
            <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">{{ t('recipients.details.sectionTitle') }}</h2>
                        <p class="mt-1 text-sm text-gray-600">{{ t('recipients.details.sectionDescription') }}</p>
                    </header>

                    <div class="mt-6 space-y-6">
                        <div class="grid gap-6 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <InputLabel :for="id('name')" :value="t('recipients.details.name')" />
                                <TextInput :id="id('name')" type="text" class="mt-1 block w-full" v-model="name" />
                                <InputError class="mt-2" :message="err('name')" />
                            </div>

                            <div>
                                <InputLabel :for="id('street')" :value="t('recipients.details.street')" />
                                <TextInput :id="id('street')" type="text" class="mt-1 block w-full" v-model="street" />
                                <InputError class="mt-2" :message="err('street')" />
                            </div>

                            <div>
                                <InputLabel :for="id('street_num')" :value="t('recipients.details.streetNum')" />
                                <TextInput :id="id('street_num')" type="text" class="mt-1 block w-full" v-model="streetNum" />
                                <InputError class="mt-2" :message="err('street_num')" />
                            </div>

                            <div>
                                <InputLabel :for="id('city')" :value="t('recipients.details.city')" />
                                <TextInput :id="id('city')" type="text" class="mt-1 block w-full" v-model="city" />
                                <InputError class="mt-2" :message="err('city')" />
                            </div>

                            <div v-if="!isInvoice">
                                <InputLabel :for="id('zip')" :value="t('recipients.details.zip')" />
                                <TextInput :id="id('zip')" type="text" class="mt-1 block w-full" v-model="zip" />
                                <InputError class="mt-2" :message="err('zip')" />
                            </div>

                            <div :class="!isInvoice ? 'sm:col-span-2' : ''">
                                <InputLabel :for="id('state')" :value="isInvoice ? t('recipients.details.stateOrZip') : t('recipients.details.country')" />
                                <TextInput
                                    v-if="isInvoice"
                                    :id="id('state')"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="state"
                                />
                                <AppSelect
                                    v-else
                                    :inputId="id('state')"
                                    v-model="state"
                                    :options="countries"
                                    option-label="name"
                                    option-value="code"
                                    :placeholder="t('recipients.details.selectCountry')"
                                    show-clear
                                    filter
                                    class="mt-1"
                                />
                                <InputError class="mt-2" :message="err('state')" />
                            </div>

                            <div>
                                <InputLabel :for="id('ico')" :value="t('recipients.details.ico')" />
                                <TextInput :id="id('ico')" type="text" class="mt-1 block w-full" v-model="ico" />
                                <InputError class="mt-2" :message="err('ico')" />
                            </div>

                            <div>
                                <InputLabel :for="id('dic')" :value="t('recipients.details.dic')" />
                                <TextInput :id="id('dic')" type="text" class="mt-1 block w-full" v-model="dic" />
                                <InputError class="mt-2" :message="err('dic')" />
                            </div>

                            <div :class="isInvoice ? 'sm:col-span-2' : ''">
                                <InputLabel :for="id('ic_dph')" :value="t('recipients.details.icDph')" />
                                <TextInput :id="id('ic_dph')" type="text" class="mt-1 block w-full" v-model="icDph" />
                                <InputError class="mt-2" :message="err('ic_dph')" />
                            </div>

                            <div :class="isInvoice ? 'sm:col-span-2' : ''">
                                <InputLabel :for="id('iban')" :value="t('recipients.details.iban')" />
                                <TextInput :id="id('iban')" type="text" class="mt-1 block w-full" v-model="iban" />
                                <InputError class="mt-2" :message="err('iban')" />
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="flex justify-end">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full min-w-[200px] rounded-lg bg-emerald-600 px-6 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 active:bg-emerald-800 disabled:cursor-not-allowed disabled:opacity-50 sm:w-auto"
                >
                    {{ form.processing ? processingLabel : submitLabel }}
                </button>
            </div>
        </form>
    </div>

    <section v-else class="grid gap-6 sm:grid-cols-2">
        <div class="sm:col-span-2">
            <InputLabel :for="id('name')" :value="t('recipients.details.name')" />
            <TextInput :id="id('name')" type="text" class="mt-1 block w-full" v-model="name" />
            <InputError class="mt-2" :message="err('name')" />
        </div>

        <div>
            <InputLabel :for="id('street')" :value="t('recipients.details.street')" />
            <TextInput :id="id('street')" type="text" class="mt-1 block w-full" v-model="street" />
        </div>

        <div>
            <InputLabel :for="id('street_num')" :value="t('recipients.details.streetNum')" />
            <TextInput :id="id('street_num')" type="text" class="mt-1 block w-full" v-model="streetNum" />
        </div>

        <div>
            <InputLabel :for="id('city')" :value="t('recipients.details.city')" />
            <TextInput :id="id('city')" type="text" class="mt-1 block w-full" v-model="city" />
        </div>

        <div v-if="!isInvoice">
            <InputLabel :for="id('zip')" :value="t('recipients.details.zip')" />
            <TextInput :id="id('zip')" type="text" class="mt-1 block w-full" v-model="zip" />
        </div>

        <div :class="!isInvoice ? 'sm:col-span-2' : ''">
            <InputLabel :for="id('state')" :value="isInvoice ? t('recipients.details.stateOrZip') : t('recipients.details.country')" />
            <TextInput
                v-if="isInvoice"
                :id="id('state')"
                type="text"
                class="mt-1 block w-full"
                v-model="state"
            />
            <AppSelect
                v-else
                :inputId="id('state')"
                v-model="state"
                :options="countries"
                option-label="name"
                option-value="code"
                :placeholder="t('recipients.details.selectCountry')"
                show-clear
                filter
                class="mt-1"
            />
        </div>

        <div>
            <InputLabel :for="id('ico')" :value="t('recipients.details.ico')" />
            <TextInput :id="id('ico')" type="text" class="mt-1 block w-full" v-model="ico" />
        </div>

        <div>
            <InputLabel :for="id('dic')" :value="t('recipients.details.dic')" />
            <TextInput :id="id('dic')" type="text" class="mt-1 block w-full" v-model="dic" />
        </div>

        <div :class="isInvoice ? 'sm:col-span-2' : ''">
            <InputLabel :for="id('ic_dph')" :value="t('recipients.details.icDph')" />
            <TextInput :id="id('ic_dph')" type="text" class="mt-1 block w-full" v-model="icDph" />
        </div>

        <div :class="isInvoice ? 'sm:col-span-2' : ''">
            <InputLabel :for="id('iban')" :value="t('recipients.details.iban')" />
            <TextInput :id="id('iban')" type="text" class="mt-1 block w-full" v-model="iban" />
        </div>
    </section>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import Button from 'primevue/button';
import PageHeader from '@/Components/PageHeader.vue';
import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import AppSelect from '@/Components/AppSelect.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { getCountries, prefixedId } from '@/Pages/Invoices/Utils/helpers';
import { useRecipientForm } from '../Composables/useRecipientForm';
import type { InvoiceRecipientData, RecipientResource } from '@/types';

const { t, locale } = useI18n();

type FormProxy = Record<string, string | null | undefined>

interface Props {
    variant?: 'page' | 'section'
    fieldsMode?: 'recipient' | 'invoice'
    modelValue?: InvoiceRecipientData | null
    errors?: Record<string, string | undefined>
    idPrefix?: string
    mode?: 'create' | 'edit'
    recipient?: RecipientResource | null
    fromInvoice?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    variant: 'page',
    fieldsMode: 'recipient',
    modelValue: null,
    errors: () => ({}),
    idPrefix: 'recipient',
    mode: 'create',
    recipient: null,
    fromInvoice: false,
});

const {
    form,
    heading,
    submitLabel,
    processingLabel,
    submit,
} = useRecipientForm(props);

const model = computed<FormProxy | null>(() =>
    props.variant === 'page'
        ? (form as unknown as FormProxy)
        : (props.modelValue as FormProxy | null) ?? null
);
const fieldErrors = computed<Record<string, string | undefined>>(() =>
    props.variant === 'page'
        ? (form.errors as Record<string, string | undefined>)
        : (props.errors ?? {})
);

function id(name: string): string {
    return prefixedId(props.idPrefix, name);
}

const isInvoice = computed(() => props.fieldsMode === 'invoice');

const countries = computed(() => getCountries(locale.value));

function proxy(getKey: () => string, setKey: () => string) {
    return computed<string>({
        get: () => String(model.value?.[getKey()] ?? ''),
        set: (v: string) => {
            if (!model.value) return;
            model.value[setKey()] = v;
        },
    });
}

    const name = proxy(
        () => (isInvoice.value ? 'recipient_name' : 'company_name'),
        () => (isInvoice.value ? 'recipient_name' : 'company_name')
    );
    const street = proxy(
        () => (isInvoice.value ? 'recipient_street' : 'street'),
        () => (isInvoice.value ? 'recipient_street' : 'street')
    );
    const streetNum = proxy(
        () => (isInvoice.value ? 'recipient_street_num' : 'street_num'),
        () => (isInvoice.value ? 'recipient_street_num' : 'street_num')
    );
    const city = proxy(
        () => (isInvoice.value ? 'recipient_city' : 'city'),
        () => (isInvoice.value ? 'recipient_city' : 'city')
    );
    const zip = proxy(() => 'zip', () => 'zip');
    const state = proxy(
        () => (isInvoice.value ? 'recipient_state' : 'state'),
        () => (isInvoice.value ? 'recipient_state' : 'state')
    );
    const ico = proxy(
        () => (isInvoice.value ? 'recipient_ico' : 'ico'),
        () => (isInvoice.value ? 'recipient_ico' : 'ico')
    );
    const dic = proxy(
        () => (isInvoice.value ? 'recipient_dic' : 'dic'),
        () => (isInvoice.value ? 'recipient_dic' : 'dic')
    );
    const icDph = proxy(
        () => (isInvoice.value ? 'recipient_ic_dph' : 'ic_dph'),
        () => (isInvoice.value ? 'recipient_ic_dph' : 'ic_dph')
    );
    const iban = proxy(
        () => (isInvoice.value ? 'recipient_iban' : 'iban'),
        () => (isInvoice.value ? 'recipient_iban' : 'iban')
    );

function err(key: string): string | undefined {
    if (!fieldErrors.value) return undefined;
    if (isInvoice.value) {
        if (key === 'name') return fieldErrors.value.recipient_name;
        return fieldErrors.value[`recipient_${key}`];
    }
    if (key === 'name') return fieldErrors.value.company_name || fieldErrors.value.name;
    return fieldErrors.value[key];
}
</script>
