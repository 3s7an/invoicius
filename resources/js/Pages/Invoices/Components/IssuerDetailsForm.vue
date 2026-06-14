<script setup lang="ts">
import { computed, reactive, watch } from 'vue';
import AppSelect from '@/Components/AppSelect.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { getCountriesSk, prefixedId } from '@/Pages/Invoices/Utils/helpers';
import type { InvoiceIssuerData } from '@/types';

interface Props {
    modelValue: InvoiceIssuerData
    idPrefix?: string
    errors?: Record<string, string | undefined>
}

const props = withDefaults(defineProps<Props>(), {
    idPrefix: 'issuer',
    errors: () => ({}),
});

const emit = defineEmits<{ 'update:modelValue': [value: InvoiceIssuerData] }>();

const local = reactive({ ...props.modelValue });

watch(() => props.modelValue, (val) => Object.assign(local, val), { deep: true });
watch(local, (val) => emit('update:modelValue', { ...val }), { deep: true });

const countries = computed(() => getCountriesSk());

const id = (name: string): string => prefixedId(props.idPrefix, name);
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
                        v-model="local.name"
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
                        v-model="local.ico"
                        autocomplete="off"
                    />
                </div>
                <div>
                    <InputLabel :for="id('dic')" value="DIČ" />
                    <TextInput
                        :id="id('dic')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="local.dic"
                        autocomplete="off"
                    />
                </div>
                <div class="sm:col-span-2">
                    <InputLabel :for="id('ic_dph')" value="IČ DPH" />
                    <TextInput
                        :id="id('ic_dph')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="local.ic_dph"
                        autocomplete="off"
                    />
                </div>
                <div>
                    <InputLabel :for="id('street')" value="Ulica" />
                    <TextInput
                        :id="id('street')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="local.street"
                        autocomplete="street-address"
                    />
                </div>
                <div>
                    <InputLabel :for="id('street_num')" value="Číslo" />
                    <TextInput
                        :id="id('street_num')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="local.street_num"
                        autocomplete="off"
                    />
                </div>
                <div>
                    <InputLabel :for="id('city')" value="Mesto" />
                    <TextInput
                        :id="id('city')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="local.city"
                        autocomplete="address-level2"
                    />
                </div>
                <div>
                    <InputLabel :for="id('zip')" value="PSČ" />
                    <TextInput
                        :id="id('zip')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="local.zip"
                        autocomplete="postal-code"
                    />
                </div>
                <div class="sm:col-span-2">
                    <InputLabel :for="id('state')" value="Krajina" />
                    <AppSelect
                        :inputId="id('state')"
                        v-model="local.state"
                        :options="countries"
                        option-label="name"
                        option-value="code"
                        placeholder="Vyberte krajinu"
                        show-clear
                        filter
                        class="mt-1"
                    />
                </div>
            </div>
        </div>
    </section>
</template>
