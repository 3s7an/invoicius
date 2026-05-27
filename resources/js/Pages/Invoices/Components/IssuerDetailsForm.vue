<script setup>
import { computed } from 'vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { getCountriesSk, prefixedId } from '@/Pages/Invoices/Utils/helpers';

const props = defineProps({
    modelValue: {
        type: Object,
        required: true,
    },
    idPrefix: {
        type: String,
        default: 'issuer',
    },
    errors: {
        type: Object,
        default: () => ({}),
    },
});

const countries = computed(() => getCountriesSk());

const id = (name) => prefixedId(props.idPrefix, name);

const selectClass =
    'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500';
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Fakturačné údaje
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                Adresa vystaviteľa na faktúre.
            </p>
        </header>

        <div class="mt-6 space-y-6">
            <div class="grid gap-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <InputLabel :for="id('name')" value="Názov / firma" />
                    <TextInput
                        :id="id('name')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.name"
                        autocomplete="name"
                    />
                    <InputError class="mt-2" :message="errors.issuer_name" />
                </div>
                <div>
                    <InputLabel :for="id('ico')" value="IČO" />
                    <TextInput
                        :id="id('ico')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.ico"
                        autocomplete="off"
                    />
                </div>
                <div>
                    <InputLabel :for="id('dic')" value="DIČ" />
                    <TextInput
                        :id="id('dic')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.dic"
                        autocomplete="off"
                    />
                </div>
                <div class="sm:col-span-2">
                    <InputLabel :for="id('ic_dph')" value="IČ DPH" />
                    <TextInput
                        :id="id('ic_dph')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.ic_dph"
                        autocomplete="off"
                    />
                </div>
                <div>
                    <InputLabel :for="id('street')" value="Ulica" />
                    <TextInput
                        :id="id('street')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.street"
                        autocomplete="street-address"
                    />
                </div>
                <div>
                    <InputLabel :for="id('street_num')" value="Číslo" />
                    <TextInput
                        :id="id('street_num')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.street_num"
                        autocomplete="off"
                    />
                </div>
                <div>
                    <InputLabel :for="id('city')" value="Mesto" />
                    <TextInput
                        :id="id('city')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.city"
                        autocomplete="address-level2"
                    />
                </div>
                <div>
                    <InputLabel :for="id('zip')" value="PSČ" />
                    <TextInput
                        :id="id('zip')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="modelValue.zip"
                        autocomplete="postal-code"
                    />
                </div>
                <div class="sm:col-span-2">
                    <InputLabel :for="id('state')" value="Krajina" />
                    <select
                        :id="id('state')"
                        v-model="modelValue.state"
                        :class="selectClass"
                        autocomplete="country"
                    >
                        <option value="">Vyberte krajinu</option>
                        <option v-for="c in countries" :key="c.code" :value="c.code">
                            {{ c.name }}
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </section>
</template>
