<template>
    <div v-if="variant === 'page'" class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h1 class="text-lg font-medium text-gray-900">{{ heading }}</h1>
            <Link :href="route('recipients.index')">
                <Button label="Späť na odberateľov" icon="pi pi-arrow-left" class="p-button-raised p-button-sm" />
            </Link>
        </div>

        <form class="space-y-8" @submit.prevent="submit">
            <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">Údaje o odberateľovi</h2>
                        <p class="mt-1 text-sm text-gray-600">Názov, adresa a identifikátory odberateľa.</p>
                    </header>

                    <div class="mt-6 space-y-6">
                        <div class="grid gap-6 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <InputLabel :for="id('name')" value="Názov / obchodné meno" />
                                <TextInput :id="id('name')" type="text" class="mt-1 block w-full" v-model="name" />
                                <InputError class="mt-2" :message="err('name')" />
                            </div>

                            <div>
                                <InputLabel :for="id('street')" value="Ulica" />
                                <TextInput :id="id('street')" type="text" class="mt-1 block w-full" v-model="street" />
                                <InputError class="mt-2" :message="err('street')" />
                            </div>

                            <div>
                                <InputLabel :for="id('street_num')" value="Číslo ulice" />
                                <TextInput :id="id('street_num')" type="text" class="mt-1 block w-full" v-model="streetNum" />
                                <InputError class="mt-2" :message="err('street_num')" />
                            </div>

                            <div>
                                <InputLabel :for="id('city')" value="Mesto" />
                                <TextInput :id="id('city')" type="text" class="mt-1 block w-full" v-model="city" />
                                <InputError class="mt-2" :message="err('city')" />
                            </div>

                            <div v-if="!isInvoice">
                                <InputLabel :for="id('zip')" value="PSČ" />
                                <TextInput :id="id('zip')" type="text" class="mt-1 block w-full" v-model="zip" />
                                <InputError class="mt-2" :message="err('zip')" />
                            </div>

                            <div :class="!isInvoice ? 'sm:col-span-2' : ''">
                                <InputLabel :for="id('state')" :value="isInvoice ? 'Štát / PSČ' : 'Štát / krajina'" />
                                <TextInput :id="id('state')" type="text" class="mt-1 block w-full" v-model="state" />
                                <InputError class="mt-2" :message="err('state')" />
                            </div>

                            <div>
                                <InputLabel :for="id('ico')" value="IČO" />
                                <TextInput :id="id('ico')" type="text" class="mt-1 block w-full" v-model="ico" />
                                <InputError class="mt-2" :message="err('ico')" />
                            </div>

                            <div>
                                <InputLabel :for="id('dic')" value="DIČ" />
                                <TextInput :id="id('dic')" type="text" class="mt-1 block w-full" v-model="dic" />
                                <InputError class="mt-2" :message="err('dic')" />
                            </div>

                            <div :class="isInvoice ? 'sm:col-span-2' : ''">
                                <InputLabel :for="id('ic_dph')" value="IČ DPH" />
                                <TextInput :id="id('ic_dph')" type="text" class="mt-1 block w-full" v-model="icDph" />
                                <InputError class="mt-2" :message="err('ic_dph')" />
                            </div>

                            <div :class="isInvoice ? 'sm:col-span-2' : ''">
                                <InputLabel :for="id('iban')" value="IBAN" />
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
                    class="w-full min-w-[200px] rounded-lg bg-gray-800 px-6 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900 disabled:cursor-not-allowed disabled:opacity-50 sm:w-auto"
                >
                    {{ form.processing ? processingLabel : submitLabel }}
                </button>
            </div>
        </form>
    </div>

    <section v-else class="grid gap-6 sm:grid-cols-2">
        <div class="sm:col-span-2">
            <InputLabel :for="id('name')" value="Názov / obchodné meno" />
            <TextInput :id="id('name')" type="text" class="mt-1 block w-full" v-model="name" />
            <InputError class="mt-2" :message="err('name')" />
        </div>

        <div>
            <InputLabel :for="id('street')" value="Ulica" />
            <TextInput :id="id('street')" type="text" class="mt-1 block w-full" v-model="street" />
        </div>

        <div>
            <InputLabel :for="id('street_num')" value="Číslo ulice" />
            <TextInput :id="id('street_num')" type="text" class="mt-1 block w-full" v-model="streetNum" />
        </div>

        <div>
            <InputLabel :for="id('city')" value="Mesto" />
            <TextInput :id="id('city')" type="text" class="mt-1 block w-full" v-model="city" />
        </div>

        <div v-if="!isInvoice">
            <InputLabel :for="id('zip')" value="PSČ" />
            <TextInput :id="id('zip')" type="text" class="mt-1 block w-full" v-model="zip" />
        </div>

        <div :class="!isInvoice ? 'sm:col-span-2' : ''">
            <InputLabel :for="id('state')" :value="isInvoice ? 'Štát / PSČ' : 'Štát / krajina'" />
            <TextInput :id="id('state')" type="text" class="mt-1 block w-full" v-model="state" />
        </div>

        <div>
            <InputLabel :for="id('ico')" value="IČO" />
            <TextInput :id="id('ico')" type="text" class="mt-1 block w-full" v-model="ico" />
        </div>

        <div>
            <InputLabel :for="id('dic')" value="DIČ" />
            <TextInput :id="id('dic')" type="text" class="mt-1 block w-full" v-model="dic" />
        </div>

        <div :class="isInvoice ? 'sm:col-span-2' : ''">
            <InputLabel :for="id('ic_dph')" value="IČ DPH" />
            <TextInput :id="id('ic_dph')" type="text" class="mt-1 block w-full" v-model="icDph" />
        </div>

        <div :class="isInvoice ? 'sm:col-span-2' : ''">
            <InputLabel :for="id('iban')" value="IBAN" />
            <TextInput :id="id('iban')" type="text" class="mt-1 block w-full" v-model="iban" />
        </div>
    </section>
</template>

<script setup>
import { computed } from 'vue';
import Button from 'primevue/button';
import { Link } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { prefixedId } from '@/Pages/Invoices/Utils/helpers';
import { useRecipientForm } from '../Composables/useRecipientForm';

const props = defineProps({
    variant: {
        type: String,
        default: 'page',
        validator: (v) => ['page', 'section'].includes(v),
    },
    fieldsMode: {
        type: String,
        default: 'recipient',
        validator: (v) => ['recipient', 'invoice'].includes(v),
    },
    modelValue: {
        type: Object,
        default: null,
    },
    errors: {
        type: Object,
        default: () => ({}),
    },
    idPrefix: {
        type: String,
        default: 'recipient',
    },
    mode: {
        type: String,
        default: 'create',
        validator: (value) => ['create', 'edit'].includes(value),
    },
    recipient: {
        type: Object,
        default: null,
    },
    fromInvoice: {
        type: Boolean,
        default: false,
    },
});

const {
    form,
    heading,
    submitLabel,
    processingLabel,
    submit,
} = useRecipientForm(props);

const model = computed(() => (props.variant === 'page' ? form : props.modelValue));
const fieldErrors = computed(() => (props.variant === 'page' ? form.errors : props.errors));

function id(name) {
    return prefixedId(props.idPrefix, name);
}

const isInvoice = computed(() => props.fieldsMode === 'invoice');

function proxy(getKey, setKey) {
    return computed({
        get: () => (model.value ? model.value[getKey()] : ''),
        set: (v) => {
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

function err(key) {
    if (!fieldErrors.value) return '';
    if (isInvoice.value) {
        if (key === 'name') return fieldErrors.value.recipient_name;
        return fieldErrors.value[`recipient_${key}`];
    }
    if (key === 'name') return fieldErrors.value.company_name || fieldErrors.value.name;
    return fieldErrors.value[key];
}
</script>
