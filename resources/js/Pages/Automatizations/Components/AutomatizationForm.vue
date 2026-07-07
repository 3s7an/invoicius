<script setup lang="ts">
import { computed, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import AppSelect from '@/Components/AppSelect.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { defaultAutomatizationForm, type AutomatizationFormData } from '@/Pages/Automatizations/Utils/automatizationFormDefaults';
import { AutomatizationType } from '@/Pages/Automatizations/Utils/automatizationTypes';
import { todayYMD, toYMD, ymdToDate } from '@/Pages/Invoices/Utils/helpers';
import DatePicker from 'primevue/datepicker';
import type { Automatization, AutomatizationTypeOption } from '../Utils/types';
import type { RecipientResource } from '@/types';

interface Props {
    mode?: 'create' | 'edit'
    automatization?: Automatization | null
    recipients: RecipientResource[]
    automatization_types: AutomatizationTypeOption[]
}

const props = withDefaults(defineProps<Props>(), {
    mode: 'create',
    automatization: null,
});

const { t } = useI18n();

const isEdit = computed(() => props.mode === 'edit');

const form = useForm(defaultAutomatizationForm(props.automatization ?? null));
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
const isDailySchedule = computed(
    () => props.automatization_types.find((option) => option.value === form.type)?.uses_daily_schedule ?? false,
);

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
    }
);

const recipientOptions = computed(() =>
    props.recipients.map((recipient) => ({
        value: recipient.id,
        label: recipient.company_name || recipient.name || '',
    })),
);

const inputClass =
    'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500';

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

function submit(): void {
    form.transform(submitPayload);

    if (isEdit.value) {
        form.patch(route('automatizations.update', props.automatization!.id));
        return;
    }

    form.post(route('automatizations.store'));
}

watch(
    () => [form.type, Math.max(0, Number(form.item_count) || 0)] as [string, number],
    ([type, count]) => {
        if (type !== AutomatizationType.InvoiceAutoGen) {
            return;
        }

        while (form.item_names.length < count) form.item_names.push('');
        while (form.item_names.length > count) form.item_names.pop();
    },
    { immediate: true }
);
</script>

<template>
    <form class="space-y-8" @submit.prevent="submit">
        <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
            <section>
                <div class="space-y-6">
                    <div>
                        <InputLabel for="auto-type" :value="t('automatizations.form.type')" />
                        <AppSelect
                            inputId="auto-type"
                            v-model="form.type"
                            :options="automatization_types"
                            option-label="label"
                            option-value="value"
                            class="mt-1"
                        />
                        <InputError class="mt-2" :message="form.errors.type" />
                    </div>

                    <div v-if="form.type === AutomatizationType.InvoiceAutoGen">
                        <InputLabel for="auto-item_count" :value="t('automatizations.form.itemCount')" />
                        <input
                            id="auto-item_count"
                            type="number"
                            v-model="form.item_count"
                            :class="inputClass"
                            min="1"
                            max="20"
                            required
                        >
                        <InputError class="mt-2" :message="form.errors.item_names" />
                    </div>

                    <div v-if="form.type === AutomatizationType.InvoiceAutoGen && form.item_count > 0">
                        <InputLabel for="auto-item_count" :value="t('automatizations.form.itemNames')" />

                            <div v-for="(name, index) in form.item_names" :key="index">
                                <input
                                    :id="`auto-item_name-${index}`"
                                    type="text"
                                    v-model="form.item_names[index]"
                                    :class="inputClass"
                                    maxlength="255"
                                    required
                                >
                                <InputError class="mt-2" :message="form.errors[`item_names.${index}`]" />
                            </div>
                    </div>

                    <div v-if="form.type === AutomatizationType.InvoiceAutoGen">
                        <InputLabel for="auto-recipient" :value="t('automatizations.form.client')" />
                        <AppSelect
                            inputId="auto-recipient"
                            v-model="form.recipient_id"
                            :options="recipientOptions"
                            :placeholder="t('automatizations.form.selectClient')"
                            show-clear
                            filter
                            class="mt-1"
                        />
                        <InputError class="mt-2" :message="form.errors.recipient_id" />
                    </div>

                    <div v-if="!isDailySchedule">
                        <InputLabel for="auto-trigger" :value="t('automatizations.form.firstRunDate')" />
                        <DatePicker
                            inputId="auto-trigger"
                            :modelValue="ymdToDate(form.date_trigger)"
                            showIcon
                            iconDisplay="input"
                            fluid
                            class="mt-1"
                            @update:model-value="(d: unknown) => { form.date_trigger = (d instanceof Date) ? toYMD(d) : '' }"
                        />
                        <p v-if="form.type === AutomatizationType.InvoiceReport" class="mt-1 text-sm text-gray-500">
                            {{ t('automatizations.form.reportHint') }}
                        </p>
                        <InputError class="mt-2" :message="form.errors.date_trigger" />
                    </div>

                    <div v-else>
                        <input type="hidden" v-model="form.date_trigger">
                        <p class="text-sm text-gray-600">
                            {{ t('automatizations.form.dailyHint') }}
                        </p>
                    </div>

                    <div v-if="form.type === AutomatizationType.InvoiceDueReminder">
                        <InputLabel for="auto-offset" :value="t('automatizations.form.dueOffsetDays')" />
                        <input
                            id="auto-offset"
                            type="number"
                            step="1"
                            min="-365"
                            max="365"
                            v-model="form.due_offset_days"
                            :class="inputClass"
                        >
                        <p class="mt-1 text-sm text-gray-500">
                            {{ t('automatizations.form.dueOffsetHintBefore') }} <br>
                            {{ t('automatizations.form.dueOffsetHintAfter') }} <br>
                            {{ t('automatizations.form.dueOffsetHintOnDate') }}
                        </p>
                        <InputError class="mt-2" :message="form.errors.due_offset_days" />
                    </div>

                    <div v-if="isEdit" class="flex items-center gap-2">
                        <input
                            id="auto-active"
                            type="checkbox"
                            v-model="form.is_active"
                            class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500"
                        >
                        <InputLabel for="auto-active" :value="t('automatizations.form.active')" />
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
                {{ form.processing ? t('common.saving') : (isEdit ? t('automatizations.form.saveAutomatization') : t('automatizations.form.createAutomatization')) }}
            </button>
        </div>
    </form>
</template>
