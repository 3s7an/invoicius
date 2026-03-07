<script setup>
import { computed } from 'vue';
import { FALLBACK_COUNTRY_LIST } from '@/utils/countries';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InvoiceSettings from '@/Components/InvoiceSettings.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    mode: {
        type: String,
        default: 'profile',
        validator: (v) => ['profile', 'embed'].includes(v),
    },
    modelValue: {
        type: Object,
        default: null,
    },
    includeName: {
        type: Boolean,
        default: false,
    },
    idPrefix: {
        type: String,
        default: 'billing',
    },
    currencies: {
        type: Array,
        default: () => [],
    },
    invoiceColors: {
        type: Array,
        default: () => [],
    },
    readonly: {
        type: Boolean,
        default: false,
    },
    /** Validation errors (embed mode: issuer_name, etc.) */
    errors: {
        type: Object,
        default: () => ({}),
    },
    /** When set (profile mode), use this form instead of internal and do not wrap in <form> */
    externalForm: {
        type: Object,
        default: null,
    },
});

const user = usePage().props.auth.user;

const internalForm = useForm({
    street: user?.street ?? '',
    street_num: user?.street_num ?? '',
    city: user?.city ?? '',
    zip: user?.zip ?? '',
    state: user?.state ?? '',
    ico: user?.ico ?? '',
    dic: user?.dic ?? '',
    ic_dph: user?.ic_dph ?? '',
    currency_id: user?.currency_id ?? '',
    invoice_color_id: user?.invoice_color_id ?? '',
    company_logo: null,
    _method: 'patch',
});

const form = computed(() => props.externalForm ?? internalForm);

const countries = computed(() => {
    try {
        if (typeof Intl.supportedValuesOf !== 'function') return FALLBACK_COUNTRY_LIST;
        const codes = Intl.supportedValuesOf('region').filter((c) => c.length === 2 && c !== 'FX');
        const displayNames = new Intl.DisplayNames(['en'], { type: 'region' });
        return codes
            .map((code) => ({ code, name: displayNames.of(code) || code }))
            .sort((a, b) => a.name.localeCompare(b.name));
    } catch {
        return FALLBACK_COUNTRY_LIST;
    }
});

function id(name) {
    return `${props.idPrefix}-${name}`;
}

const invoiceColorsList = computed(() => Array.isArray(props.invoiceColors) ? props.invoiceColors : []);

const selectClass =
    'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500';
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Billing details
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                Address used as issuer on invoices (street, city, state).
            </p>
        </header>

        <!-- Profile mode: form (or div when externalForm) + content -->
        <component
            :is="mode === 'profile' && !externalForm ? 'form' : 'div'"
            v-if="mode === 'profile'"
            class="mt-6 space-y-6"
            @submit.prevent="!externalForm && internalForm.post(route('profile.details.update'), { forceFormData: true })"
        >
            <InvoiceSettings
                v-if="!externalForm"
                mode="profile"
                :form="internalForm"
                :user="user"
                :invoice-colors="invoiceColorsList"
                :id-prefix="idPrefix"
            />
            <div v-if="currencies.length" class="max-w-xs">
                <InputLabel :for="id('currency_id')" value="Default currency" />
                <select
                    :id="id('currency_id')"
                    v-model="form.currency_id"
                    :class="selectClass"
                >
                    <option value="">—</option>
                    <option
                        v-for="c in currencies"
                        :key="c.id"
                        :value="c.id"
                    >
                        {{ c.name }} ({{ c.symbol }})
                    </option>
                </select>
                <InputError class="mt-2" :message="form.errors.currency_id" />
            </div>
            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <InputLabel :for="id('ico')" value="Company ID" />
                    <TextInput
                        :id="id('ico')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.ico"
                        autocomplete="off"
                    />
                    <InputError class="mt-2" :message="form.errors.ico" />
                </div>
                <div>
                    <InputLabel :for="id('dic')" value="Tax ID" />
                    <TextInput
                        :id="id('dic')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.dic"
                        autocomplete="off"
                    />
                    <InputError class="mt-2" :message="form.errors.dic" />
                </div>
                <div class="sm:col-span-2">
                    <InputLabel :for="id('ic_dph')" value="VAT ID" />
                    <TextInput
                        :id="id('ic_dph')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.ic_dph"
                        autocomplete="off"
                    />
                    <InputError class="mt-2" :message="form.errors.ic_dph" />
                </div>
                <div>
                    <InputLabel :for="id('street')" value="Street" />
                    <TextInput
                        :id="id('street')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.street"
                        autocomplete="street-address"
                    />
                    <InputError class="mt-2" :message="form.errors.street" />
                </div>
                <div>
                    <InputLabel :for="id('street_num')" value="Number" />
                    <TextInput
                        :id="id('street_num')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.street_num"
                        autocomplete="off"
                    />
                    <InputError class="mt-2" :message="form.errors.street_num" />
                </div>
                <div>
                    <InputLabel :for="id('city')" value="City" />
                    <TextInput
                        :id="id('city')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.city"
                        autocomplete="address-level2"
                    />
                    <InputError class="mt-2" :message="form.errors.city" />
                </div>
                <div>
                    <InputLabel :for="id('zip')" value="ZIP" />
                    <TextInput
                        :id="id('zip')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.zip"
                        autocomplete="postal-code"
                    />
                    <InputError class="mt-2" :message="form.errors.zip" />
                </div>
                <div class="sm:col-span-2">
                    <InputLabel :for="id('state')" value="Country" />
                    <select
                        :id="id('state')"
                        v-model="form.state"
                        :class="selectClass"
                        autocomplete="country"
                    >
                        <option value="">Select country</option>
                        <option v-for="c in countries" :key="c.code" :value="c.code">
                            {{ c.name }}
                        </option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.state" />
                </div>
            </div>
            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">
                        Saved.
                    </p>
                </Transition>
            </div>
        </component>

        <!-- Embed mode: bound to modelValue, no submit -->
        <div v-else class="mt-6 space-y-6">
            <div class="grid gap-6 sm:grid-cols-2">
                <div v-if="includeName && modelValue" class="sm:col-span-2">
                    <InputLabel :for="id('name')" value="Name / company" />
                    <TextInput
                        :id="id('name')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.name"
                        autocomplete="name"
                        :readonly="readonly"
                    />
                    <InputError class="mt-2" :message="errors.issuer_name" />
                </div>
                <div>
                    <InputLabel :for="id('ico')" value="Company ID" />
                    <TextInput
                        :id="id('ico')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.ico"
                        autocomplete="off"
                        :readonly="readonly"
                    />
                </div>
                <div>
                    <InputLabel :for="id('dic')" value="Tax ID" />
                    <TextInput
                        :id="id('dic')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.dic"
                        autocomplete="off"
                        :readonly="readonly"
                    />
                </div>
                <div class="sm:col-span-2">
                    <InputLabel :for="id('ic_dph')" value="VAT ID" />
                    <TextInput
                        :id="id('ic_dph')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.ic_dph"
                        autocomplete="off"
                        :readonly="readonly"
                    />
                </div>
                <div>
                    <InputLabel :for="id('street')" value="Street" />
                    <TextInput
                        :id="id('street')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.street"
                        autocomplete="street-address"
                        :readonly="readonly"
                    />
                </div>
                <div>
                    <InputLabel :for="id('street_num')" value="Number" />
                    <TextInput
                        :id="id('street_num')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.street_num"
                        autocomplete="off"
                        :readonly="readonly"
                    />
                </div>
                <div>
                    <InputLabel :for="id('city')" value="City" />
                    <TextInput
                        :id="id('city')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.city"
                        autocomplete="address-level2"
                        :readonly="readonly"
                    />
                </div>
                <div>
                    <InputLabel :for="id('zip')" value="ZIP" />
                    <TextInput
                        :id="id('zip')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.zip"
                        autocomplete="postal-code"
                        :readonly="readonly"
                    />
                </div>
                <div class="sm:col-span-2">
                    <InputLabel :for="id('state')" value="Country" />
                    <select
                        :id="id('state')"
                        v-model="modelValue.state"
                        :class="selectClass"
                        :disabled="readonly"
                        autocomplete="country"
                    >
                        <option value="">Select country</option>
                        <option v-for="c in countries" :key="c.code" :value="c.code">
                            {{ c.name }}
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </section>
</template>
