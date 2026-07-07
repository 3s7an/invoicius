<template>
    <!-- eslint-disable vue/no-mutating-props -->
    <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{ t('profile.billing.title') }}</h2>
            <p class="mt-1 text-sm text-gray-600">
                {{ t('profile.billing.description') }}
            </p>
        </header>

        <div class="mt-6 space-y-6">
            <div v-if="currencies.length" class="max-w-xl">
                <InputLabel :for="id('currency_id')" :value="t('profile.billing.defaultCurrency')" />
                <AppSelect
                    :inputId="id('currency_id')"
                    v-model="form.currency_id"
                    :options="currencyOptions"
                    placeholder="—"
                    show-clear
                    class="mt-1"
                />
                <InputError class="mt-2" :message="form.errors.currency_id" />
            </div>

            <div v-if="vatTypes.length" class="max-w-xl">
                <InputLabel :for="id('default_vat_type_id')" :value="t('profile.billing.defaultVatType')" />
                <AppSelect
                    :inputId="id('default_vat_type_id')"
                    v-model="form.default_vat_type_id"
                    :options="vatTypeOptions"
                    placeholder="—"
                    show-clear
                    class="mt-1"
                />
                <InputError class="mt-2" :message="form.errors.default_vat_type_id" />
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <InputLabel :for="id('ico')" :value="t('invoices.issuer.companyRegNo')" />
                    <TextInput :id="id('ico')" type="text" class="mt-1 block w-full" v-model="form.ico" autocomplete="off" />
                    <InputError class="mt-2" :message="form.errors.ico" />
                </div>
                <div>
                    <InputLabel :for="id('dic')" :value="t('invoices.issuer.taxId')" />
                    <TextInput :id="id('dic')" type="text" class="mt-1 block w-full" v-model="form.dic" autocomplete="off" />
                    <InputError class="mt-2" :message="form.errors.dic" />
                </div>
                <div class="sm:col-span-2">
                    <InputLabel :for="id('ic_dph')" :value="t('invoices.issuer.vatId')" />
                    <TextInput :id="id('ic_dph')" type="text" class="mt-1 block w-full" v-model="form.ic_dph" autocomplete="off" />
                    <InputError class="mt-2" :message="form.errors.ic_dph" />
                </div>
                <div class="sm:col-span-2">
                    <InputLabel :for="id('iban')" value="IBAN" />
                    <div class="relative mt-1">
                        <i class="pi pi-credit-card absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm" aria-hidden="true" />
                        <TextInput :id="id('iban')" type="text" class="block w-full pl-9" v-model="form.iban" autocomplete="off" />
                    </div>
                    <InputError class="mt-2" :message="form.errors.iban" />
                </div>
                <div>
                    <InputLabel :for="id('street')" :value="t('invoices.issuer.street')" />
                    <TextInput :id="id('street')" type="text" class="mt-1 block w-full" v-model="form.street" autocomplete="street-address" />
                    <InputError class="mt-2" :message="form.errors.street" />
                </div>
                <div>
                    <InputLabel :for="id('street_num')" :value="t('invoices.issuer.streetNum')" />
                    <TextInput :id="id('street_num')" type="text" class="mt-1 block w-full" v-model="form.street_num" autocomplete="off" />
                    <InputError class="mt-2" :message="form.errors.street_num" />
                </div>
                <div>
                    <InputLabel :for="id('city')" :value="t('invoices.issuer.city')" />
                    <TextInput :id="id('city')" type="text" class="mt-1 block w-full" v-model="form.city" autocomplete="address-level2" />
                    <InputError class="mt-2" :message="form.errors.city" />
                </div>
                <div>
                    <InputLabel :for="id('zip')" :value="t('invoices.issuer.zip')" />
                    <TextInput :id="id('zip')" type="text" class="mt-1 block w-full" v-model="form.zip" autocomplete="postal-code" />
                    <InputError class="mt-2" :message="form.errors.zip" />
                </div>
                <div class="sm:col-span-2">
                    <InputLabel :for="id('state')" :value="t('invoices.issuer.country')" />
                    <AppSelect
                        :inputId="id('state')"
                        v-model="form.state"
                        :options="countries"
                        option-label="name"
                        option-value="code"
                        :placeholder="t('invoices.issuer.selectCountry')"
                        show-clear
                        filter
                        class="mt-1"
                    />
                    <InputError class="mt-2" :message="form.errors.state" />
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">{{ t('common.save') }}</PrimaryButton>
                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">{{ t('common.saved') }}</p>
                </Transition>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { formatVatTypeLabel } from '@/utils/vatTypes';
import AppSelect from '@/Components/AppSelect.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { currencyName, getCountries } from '@/Pages/Invoices/Utils/helpers';
import type { InertiaForm } from '@inertiajs/vue3';
import type { BillingDetailsFormData } from '../Utils/profileFormDefaults';
import type { CurrencyResource, VatTypeResource } from '@/types';

const { t, locale } = useI18n();

interface Props {
    form: InertiaForm<BillingDetailsFormData>
    idPrefix?: string
    currencies?: CurrencyResource[]
    vatTypes?: VatTypeResource[]
}

const props = withDefaults(defineProps<Props>(), {
    idPrefix: 'billing',
    currencies: () => [],
    vatTypes: () => [],
});

const countries = computed(() => getCountries(locale.value));

const currencyOptions = computed(() =>
    props.currencies.map((currency) => ({
        value: currency.id,
        label: `${currencyName(currency.symbol, currency.name)} (${currency.symbol})`,
    })),
);

const vatTypeOptions = computed(() =>
    props.vatTypes.map((vatType) => ({
        value: vatType.id,
        label: formatVatTypeLabel(vatType),
    })),
);

function id(name: string): string {
    return `${props.idPrefix}-${name}`;
}
</script>


