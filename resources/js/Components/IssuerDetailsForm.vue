<script setup>
import { computed } from 'vue';
import { FALLBACK_COUNTRY_LIST } from '@/utils/countries';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    modelValue: {
        type: Object,
        required: true,
    },
    idPrefix: {
        type: String,
        default: 'issuer',
    },
    readonly: {
        type: Boolean,
        default: false,
    },
    errors: {
        type: Object,
        default: () => ({}),
    },
});

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
                Issuer address as it appears on the invoice.
            </p>
        </header>

        <div class="mt-6 space-y-6">
            <div class="grid gap-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
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
