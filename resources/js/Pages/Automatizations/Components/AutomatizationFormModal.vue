<script setup lang="ts">
import { computed, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import Modal from '@/Components/Modal.vue';
import AppSelect from '@/Components/AppSelect.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import DatePicker from 'primevue/datepicker';
import { defaultAutomatizationForm, type AutomatizationFormData } from '@/Pages/Automatizations/Utils/automatizationFormDefaults';
import { AutomatizationType } from '@/Pages/Automatizations/Utils/automatizationTypes';
import { todayYMD, toYMD, ymdToDate } from '@/Pages/Invoices/Utils/helpers';
import type { Automatization, AutomatizationTypeOption } from '../Utils/types';
import type { RecipientResource } from '@/types';

interface Props {
    show: boolean
    automatization?: Automatization | null
    recipients: RecipientResource[]
    automatizationTypes: AutomatizationTypeOption[]
}

const props = withDefaults(defineProps<Props>(), { automatization: null });
const emit = defineEmits<{ close: [] }>();

const { t } = useI18n();

const isEdit = computed(() => props.automatization != null);

const form = useForm<AutomatizationFormData>(defaultAutomatizationForm(props.automatization ?? null));

const heading = computed(() => (isEdit.value ? t('automatizations.form.editTitle') : t('automatizations.form.newTitle')));
const submitLabel = computed(() => (isEdit.value ? t('automatizations.form.saveAutomatization') : t('automatizations.form.createAutomatization')));

const isDailySchedule = computed(
    () => props.automatizationTypes.find((option: AutomatizationTypeOption) => option.value === form.type)?.uses_daily_schedule ?? false,
);

const recipientOptions = computed(() =>
    props.recipients.map((r) => ({
        value: r.id,
        label: r.company_name || r.name || '',
    })),
);

function submitPayload(data: AutomatizationFormData): Record<string, unknown> {
    const payload: Record<string, unknown> = { ...data };
    delete payload.item_count;

    if (payload.type !== AutomatizationType.InvoiceAutoGen) {
        delete payload.item_names;
        delete payload.recipient_id;
    }

    if (payload.type !== AutomatizationType.InvoiceDueReminder) {
        delete payload.due_offset_days;
    }

    return payload;
}

function submitModal(): void {
    const request = form.transform(submitPayload);
    if (isEdit.value && props.automatization) {
        request.put(route('automatizations.update', props.automatization.id), {
            preserveScroll: true,
            onSuccess: () => emit('close'),
        });
    } else {
        request.post(route('automatizations.store'), {
            preserveScroll: true,
            onSuccess: () => emit('close'),
        });
    }
}

watch(
    () => form.type,
    (type) => {
        if (type === AutomatizationType.InvoiceReport) {
            form.recipient_id = '';
        }
        if (type !== AutomatizationType.InvoiceDueReminder) {
            form.due_offset_days = '';
        }
        if (isDailySchedule.value) {
            form.date_trigger = todayYMD();
        }
        if (type !== AutomatizationType.InvoiceAutoGen) {
            form.item_names = [];
            form.item_count = 1;
        } else if (form.item_names.length === 0) {
            form.item_names = [''];
            form.item_count = 1;
        }
    },
);

watch(
    () => [form.type, Math.max(0, Number(form.item_count) || 0)] as [string, number],
    ([type, count]) => {
        if (type !== AutomatizationType.InvoiceAutoGen) return;
        while (form.item_names.length < count) form.item_names.push('');
        while (form.item_names.length > count) form.item_names.pop();
    },
    { immediate: true },
);
</script>

<template>
    <Modal :show="show" max-width="lg" @close="emit('close')">
        <!-- Header -->
        <div class="flex shrink-0 items-start gap-3 border-b border-gray-200 px-6 py-4">
            <div class="flex-1">
                <h2 class="text-xl font-semibold text-gray-900">{{ heading }}</h2>
                <p class="mt-0.5 text-[13px] text-gray-500">{{ t('automatizations.form.modalDescription') }}</p>
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
            <!-- ── Typ automatizácie ── -->
            <div class="flex items-center gap-2.5 mb-3.5 mt-[22px]">
                <span class="text-[11px] font-semibold uppercase tracking-[0.08em] text-gray-400 whitespace-nowrap">{{ t('automatizations.form.typeSection') }}</span>
                <span class="flex-1 h-px bg-gray-200" />
            </div>

            <div v-if="!isEdit" class="flex flex-col gap-2.5">
                <label
                    v-for="card in automatizationTypes"
                    :key="card.value"
                    class="flex cursor-pointer items-start gap-3.5 rounded-md border-2 p-3 transition"
                    :class="form.type === card.value
                        ? 'border-emerald-500 bg-emerald-50'
                        : 'border-gray-200 bg-white hover:border-gray-300'"
                >
                    <input
                        type="radio"
                        name="auto_type"
                        :value="card.value"
                        v-model="form.type"
                        class="mt-0.5 shrink-0 accent-emerald-500"
                    >
                    <span
                        class="flex h-[34px] w-[34px] shrink-0 items-center justify-center rounded-md transition"
                        :class="form.type === card.value
                            ? 'bg-emerald-500 text-white'
                            : 'bg-gray-100 text-gray-500'"
                    >
                        <i :class="`pi ${card.icon}`" class="text-[15px]" aria-hidden="true" />
                    </span>
                    <div>
                        <div class="text-sm font-semibold text-gray-900">{{ card.label }}</div>
                        <div class="mt-0.5 text-[13px] text-gray-500">{{ card.description }}</div>
                    </div>
                </label>
                <InputError class="mt-1" :message="form.errors.type" />
            </div>

            <div v-else class="rounded-lg bg-gray-50 px-4 py-3 text-sm text-gray-700">
                {{ t('automatizations.form.typeLabel') }} <span class="font-medium">{{ automatization?.type_label }}</span>
            </div>

            <!-- ── Nastavenia ── -->
            <div class="flex items-center gap-2.5 mb-3.5 mt-[22px]">
                <span class="text-[11px] font-semibold uppercase tracking-[0.08em] text-gray-400 whitespace-nowrap">{{ t('automatizations.form.settingsSection') }}</span>
                <span class="flex-1 h-px bg-gray-200" />
            </div>

            <div class="flex flex-col gap-3.5">
                <div v-if="form.type === AutomatizationType.InvoiceAutoGen">
                    <InputLabel for="modal-auto-item_count" :value="t('automatizations.form.itemCount')" />
                    <TextInput
                        id="modal-auto-item_count"
                        v-model="form.item_count"
                        type="number"
                        class="mt-1 block w-full"
                        min="1"
                        max="20"
                    />
                    <InputError class="mt-1" :message="form.errors.item_names" />
                </div>

                <div v-if="form.type === AutomatizationType.InvoiceAutoGen && form.item_count > 0">
                    <InputLabel :value="t('automatizations.form.itemNames')" />
                    <div v-for="(_, index) in form.item_names" :key="index" class="mt-1.5">
                        <TextInput
                            :id="`modal-auto-item_name-${index}`"
                            v-model="form.item_names[index]"
                            type="text"
                            class="block w-full"
                            maxlength="255"
                        />
                        <InputError class="mt-1" :message="form.errors[`item_names.${index}`]" />
                    </div>
                </div>

                <div v-if="form.type === AutomatizationType.InvoiceAutoGen">
                    <InputLabel for="modal-auto-recipient" :value="t('automatizations.form.client')" />
                    <AppSelect
                        inputId="modal-auto-recipient"
                        v-model="form.recipient_id"
                        :options="recipientOptions"
                        :placeholder="t('automatizations.form.selectClient')"
                        show-clear
                        filter
                        class="mt-1"
                    />
                    <InputError class="mt-1" :message="form.errors.recipient_id" />
                </div>

                <div v-if="!isDailySchedule">
                    <InputLabel for="modal-auto-trigger" :value="t('automatizations.form.firstRunDate')" />
                    <DatePicker
                        inputId="modal-auto-trigger"
                        :modelValue="ymdToDate(form.date_trigger)"
                        showIcon
                        iconDisplay="input"
                        fluid
                        class="mt-1"
                        @update:model-value="(d: unknown) => { form.date_trigger = (d instanceof Date) ? toYMD(d) : '' }"
                    />
                    <p v-if="form.type === AutomatizationType.InvoiceReport" class="mt-1 text-xs text-gray-500">
                        {{ t('automatizations.form.reportHint') }}
                    </p>
                    <InputError class="mt-1" :message="form.errors.date_trigger" />
                </div>

                <div v-else>
                    <input type="hidden" v-model="form.date_trigger">
                    <p class="text-sm text-gray-600">
                        {{ t('automatizations.form.dailyHint') }}
                    </p>
                </div>

                <div v-if="form.type === AutomatizationType.InvoiceDueReminder">
                    <InputLabel for="modal-auto-offset" :value="t('automatizations.form.dueOffsetDays')" />
                    <TextInput
                        id="modal-auto-offset"
                        v-model="form.due_offset_days"
                        type="number"
                        step="1"
                        min="-365"
                        max="365"
                        class="mt-1 block w-full"
                    />
                    <p class="mt-1 text-xs text-gray-500">
                        {{ t('automatizations.form.dueOffsetHintBefore') }} &nbsp;|&nbsp; {{ t('automatizations.form.dueOffsetHintAfter') }} &nbsp;|&nbsp; {{ t('automatizations.form.dueOffsetHintOnDate') }}
                    </p>
                    <InputError class="mt-1" :message="form.errors.due_offset_days" />
                </div>

                <div v-if="isEdit" class="flex items-center gap-2 pt-1">
                    <input
                        id="modal-auto-active"
                        type="checkbox"
                        v-model="form.is_active"
                        class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500"
                    >
                    <InputLabel for="modal-auto-active" :value="t('automatizations.form.active')" class="mb-0" />
                </div>
            </div>
        </form>

        <!-- Footer -->
        <div class="flex shrink-0 items-center justify-end gap-2 border-t border-gray-200 bg-gray-50 px-6 py-3.5">
            <SecondaryButton type="button" @click="emit('close')">{{ t('common.cancel') }}</SecondaryButton>
            <PrimaryButton type="button" :disabled="form.processing" @click="submitModal">
                {{ form.processing ? t('common.saving') : submitLabel }}
            </PrimaryButton>
        </div>
    </Modal>
</template>
