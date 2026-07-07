<script setup lang="ts">
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import Modal from '@/Components/Modal.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import AppSelect from '@/Components/AppSelect.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { getCountries } from '@/Pages/Invoices/Utils/helpers';
import { useRecipientForm } from '../Composables/useRecipientForm';
import { recipientPayload } from '../Utils/recipientFormDefaults';
import type { RecipientResource } from '@/types';

const { t, locale } = useI18n();

interface Props {
    show: boolean
    recipient?: RecipientResource | null
}

const props = withDefaults(defineProps<Props>(), { recipient: null });
const emit = defineEmits<{ close: [] }>();

const isEdit = computed(() => props.recipient != null);

const { form, submitLabel, processingLabel } = useRecipientForm({
    mode: isEdit.value ? 'edit' : 'create',
    recipient: props.recipient,
});

const heading = computed(() => (isEdit.value ? t('recipients.modal.editTitle') : t('recipients.modal.newTitle')));
const countries = computed(() => getCountries(locale.value));

function submitModal() {
    const request = form.transform(recipientPayload);
    if (isEdit.value && props.recipient) {
        request.put(route('recipients.update', props.recipient.id), {
            preserveScroll: true,
            onSuccess: () => emit('close'),
        });
    } else {
        request.post(route('recipients.store'), {
            preserveScroll: true,
            onSuccess: () => emit('close'),
        });
    }
}
</script>

<template>
    <Modal :show="show" max-width="xl" @close="emit('close')">
        <!-- Header -->
        <div class="flex shrink-0 items-start gap-3 border-b border-gray-200 px-6 py-4">
            <div class="flex-1">
                <h2 class="text-xl font-semibold text-gray-900">{{ heading }}</h2>
                <p class="mt-0.5 text-[13px] text-gray-500">{{ t('recipients.modal.description') }}</p>
            </div>
            <button
                type="button"
                class="mt-0.5 shrink-0 rounded p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600"
                @click="emit('close')"
            >
                <i class="pi pi-times text-xl" aria-hidden="true" />
                <span class="sr-only">{{ t('common.close') }}</span>
            </button>
        </div>

        <!-- Body -->
        <form class="overflow-y-auto px-6 pb-5 pt-1" @submit.prevent="submitModal">
            <!-- ── Firemné údaje ── -->
            <div class="flex items-center gap-2.5 mb-3.5 mt-[22px]">
                <span class="text-[11px] font-semibold uppercase tracking-[0.08em] text-gray-400 whitespace-nowrap">{{ t('recipients.modal.companyDetails') }}</span>
                <span class="flex-1 h-px bg-gray-200" />
            </div>

            <div class="flex flex-col gap-3.5">
                <div>
                    <InputLabel for="modal-name" :value="t('recipients.modal.companyName')" />
                    <div class="relative mt-1">
                        <i class="pi pi-building absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true" />
                        <TextInput
                            id="modal-name"
                            v-model="form.company_name"
                            type="text"
                            class="block w-full pl-9"
                        />
                    </div>
                    <InputError class="mt-1" :message="form.errors.company_name || form.errors.name" />
                </div>

                <div>
                    <InputLabel for="modal-email" :value="t('recipients.modal.email')" />
                    <div class="relative mt-1">
                        <i class="pi pi-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true" />
                        <TextInput
                            id="modal-email"
                            v-model="form.email"
                            type="email"
                            class="block w-full pl-9"
                        />
                    </div>
                    <InputError class="mt-1" :message="form.errors.email" />
                </div>

                <div class="grid grid-cols-2 gap-3.5">
                    <div>
                        <InputLabel for="modal-ico" :value="t('recipients.details.ico')" />
                        <TextInput id="modal-ico" v-model="form.ico" type="text" class="mt-1 block w-full" />
                        <InputError class="mt-1" :message="form.errors.ico" />
                    </div>
                    <div>
                        <InputLabel for="modal-dic" :value="t('recipients.details.dic')" />
                        <TextInput id="modal-dic" v-model="form.dic" type="text" class="mt-1 block w-full" />
                        <InputError class="mt-1" :message="form.errors.dic" />
                    </div>
                </div>

                <div>
                    <InputLabel for="modal-ic_dph" :value="t('recipients.details.icDph')" />
                    <TextInput id="modal-ic_dph" v-model="form.ic_dph" type="text" class="mt-1 block w-full" />
                    <p class="mt-1 text-xs text-gray-500">{{ t('recipients.modal.icDphHint') }}</p>
                    <InputError class="mt-1" :message="form.errors.ic_dph" />
                </div>

                <div>
                    <InputLabel for="modal-iban" :value="t('recipients.details.iban')" />
                    <TextInput id="modal-iban" v-model="form.iban" type="text" class="mt-1 block w-full" />
                    <InputError class="mt-1" :message="form.errors.iban" />
                </div>
            </div>

            <!-- ── Adresa ── -->
            <div class="flex items-center gap-2.5 mb-3.5 mt-[22px]">
                <span class="text-[11px] font-semibold uppercase tracking-[0.08em] text-gray-400 whitespace-nowrap">{{ t('recipients.modal.address') }}</span>
                <span class="flex-1 h-px bg-gray-200" />
            </div>

            <div class="flex flex-col gap-3.5">
                <div class="grid grid-cols-[2fr_1fr] gap-3.5">
                    <div>
                        <InputLabel for="modal-street" :value="t('recipients.details.street')" />
                        <div class="relative mt-1">
                            <i class="pi pi-map-marker absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true" />
                            <TextInput
                                id="modal-street"
                                v-model="form.street"
                                type="text"
                                class="block w-full pl-9"
                            />
                        </div>
                        <InputError class="mt-1" :message="form.errors.street" />
                    </div>
                    <div>
                        <InputLabel for="modal-street_num" :value="t('recipients.details.streetNum')" />
                        <TextInput id="modal-street_num" v-model="form.street_num" type="text" class="mt-1 block w-full" />
                        <InputError class="mt-1" :message="form.errors.street_num" />
                    </div>
                </div>

                <div class="grid grid-cols-[2fr_1fr] gap-3.5">
                    <div>
                        <InputLabel for="modal-city" :value="t('recipients.details.city')" />
                        <TextInput id="modal-city" v-model="form.city" type="text" class="mt-1 block w-full" />
                        <InputError class="mt-1" :message="form.errors.city" />
                    </div>
                    <div>
                        <InputLabel for="modal-zip" :value="t('recipients.details.zip')" />
                        <TextInput id="modal-zip" v-model="form.zip" type="text" class="mt-1 block w-full" />
                        <InputError class="mt-1" :message="form.errors.zip" />
                    </div>
                </div>

                <div>
                    <InputLabel for="modal-state" :value="t('recipients.details.country')" />
                    <AppSelect
                        inputId="modal-state"
                        v-model="form.state"
                        :options="countries"
                        option-label="name"
                        option-value="code"
                        :placeholder="t('recipients.details.selectCountry')"
                        show-clear
                        filter
                        class="mt-1"
                    />
                    <InputError class="mt-1" :message="form.errors.state" />
                </div>
            </div>
        </form>

        <!-- Footer -->
        <div class="flex shrink-0 items-center justify-end gap-2 border-t border-gray-200 bg-gray-50 px-6 py-3.5">
            <SecondaryButton type="button" @click="emit('close')">{{ t('common.cancel') }}</SecondaryButton>
            <PrimaryButton type="button" :disabled="form.processing" @click="submitModal">
                {{ form.processing ? processingLabel : submitLabel }}
            </PrimaryButton>
        </div>
    </Modal>
</template>
