<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    /** Reactive object to bind. Invoice: recipient_name, recipient_street, ...; Recipient: name, company_name, street, ... */
    modelValue: {
        type: Object,
        required: true,
    },
    /** 'invoice' = recipient_* fields (name, address, IČO, DIČ, IČ DPH, IBAN); 'recipient' = full recipient form */
    mode: {
        type: String,
        default: 'invoice',
        validator: (v) => ['invoice', 'recipient'].includes(v),
    },
    /** Prefix for input ids to avoid clashes */
    idPrefix: {
        type: String,
        default: 'recipient',
    },
    /** Validation errors object (e.g. form.errors) - only used in recipient mode */
    errors: {
        type: Object,
        default: () => ({}),
    },
});

function id(name) {
    return `${props.idPrefix}-${name}`;
}
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Recipient details
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                <template v-if="mode === 'invoice'">
                    Address of the recipient (customer) on the invoice.
                </template>
                <template v-else>
                    Name, address and identification of the recipient.
                </template>
            </p>
        </header>

        <div class="mt-6 space-y-6">
            <!-- Invoice mode: recipient_* (name, address, tax ids, IBAN) -->
            <div v-if="mode === 'invoice'" class="grid gap-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <InputLabel :for="id('name')" value="Name / company" />
                    <TextInput
                        :id="id('name')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.recipient_name"
                        autocomplete="name"
                    />
                    <InputError class="mt-2" :message="errors.recipient_name" />
                </div>
                <div class="sm:col-span-2">
                    <InputLabel :for="id('street')" value="Street" />
                    <TextInput
                        :id="id('street')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.recipient_street"
                        autocomplete="street-address"
                    />
                </div>
                <div>
                    <InputLabel :for="id('street_num')" value="Number" />
                    <TextInput
                        :id="id('street_num')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.recipient_street_num"
                        autocomplete="off"
                    />
                </div>
                <div>
                    <InputLabel :for="id('city')" value="City" />
                    <TextInput
                        :id="id('city')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.recipient_city"
                        autocomplete="address-level2"
                    />
                </div>
                <div class="sm:col-span-2">
                    <InputLabel :for="id('state')" value="State / ZIP" />
                    <TextInput
                        :id="id('state')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.recipient_state"
                        autocomplete="address-level1"
                    />
                </div>
                <div>
                    <InputLabel :for="id('ico')" value="Company ID (IČO)" />
                    <TextInput
                        :id="id('ico')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.recipient_ico"
                        autocomplete="off"
                    />
                </div>
                <div>
                    <InputLabel :for="id('dic')" value="Tax ID (DIČ)" />
                    <TextInput
                        :id="id('dic')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.recipient_dic"
                        autocomplete="off"
                    />
                </div>
                <div class="sm:col-span-2">
                    <InputLabel :for="id('ic_dph')" value="VAT ID (IČ DPH)" />
                    <TextInput
                        :id="id('ic_dph')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.recipient_ic_dph"
                        autocomplete="off"
                    />
                </div>
                <div class="sm:col-span-2">
                    <InputLabel :for="id('iban')" value="IBAN" />
                    <TextInput
                        :id="id('iban')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.recipient_iban"
                        autocomplete="off"
                    />
                </div>
            </div>

            <!-- Recipient mode: full form (name/company = one field, street, ...) -->
            <div v-else class="grid gap-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <InputLabel :for="id('name')" value="Name / Company name" />
                    <TextInput
                        :id="id('name')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.company_name"
                        autocomplete="organization"
                    />
                    <InputError class="mt-2" :message="errors.company_name || errors.name" />
                </div>
                <div>
                    <InputLabel :for="id('street')" value="Street" />
                    <TextInput
                        :id="id('street')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.street"
                        autocomplete="street-address"
                    />
                    <InputError class="mt-2" :message="errors.street" />
                </div>
                <div>
                    <InputLabel :for="id('street_num')" value="Street number" />
                    <TextInput
                        :id="id('street_num')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.street_num"
                    />
                    <InputError class="mt-2" :message="errors.street_num" />
                </div>
                <div>
                    <InputLabel :for="id('city')" value="City" />
                    <TextInput
                        :id="id('city')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.city"
                        autocomplete="address-level2"
                    />
                    <InputError class="mt-2" :message="errors.city" />
                </div>
                <div>
                    <InputLabel :for="id('zip')" value="ZIP / Postal code" />
                    <TextInput
                        :id="id('zip')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.zip"
                        autocomplete="postal-code"
                    />
                    <InputError class="mt-2" :message="errors.zip" />
                </div>
                <div class="sm:col-span-2">
                    <InputLabel :for="id('state')" value="State / Country" />
                    <TextInput
                        :id="id('state')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.state"
                        autocomplete="address-level1"
                    />
                    <InputError class="mt-2" :message="errors.state" />
                </div>
                <div>
                    <InputLabel :for="id('ico')" value="Company ID (IČO)" />
                    <TextInput
                        :id="id('ico')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.ico"
                    />
                    <InputError class="mt-2" :message="errors.ico" />
                </div>
                <div>
                    <InputLabel :for="id('dic')" value="Tax ID (DIČ)" />
                    <TextInput
                        :id="id('dic')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.dic"
                    />
                    <InputError class="mt-2" :message="errors.dic" />
                </div>
                <div>
                    <InputLabel :for="id('ic_dph')" value="VAT ID (IČ DPH)" />
                    <TextInput
                        :id="id('ic_dph')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.ic_dph"
                    />
                    <InputError class="mt-2" :message="errors.ic_dph" />
                </div>
                <div>
                    <InputLabel :for="id('iban')" value="IBAN" />
                    <TextInput
                        :id="id('iban')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.iban"
                    />
                    <InputError class="mt-2" :message="errors.iban" />
                </div>
            </div>
        </div>
    </section>
</template>
