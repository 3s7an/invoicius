<script setup>
import { computed, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { defaultAutomatizationForm, automatizationTypeLabel } from '@/Pages/Automatizations/Utils/automatizationFormDefaults';
import { todayYMD } from '@/Pages/Invoices/Utils/helpers';

const props = defineProps({
    mode: {
        type: String,
        default: 'create',
        validator: (v) => ['create', 'edit'].includes(v),
    },
    automatization: {
        type: Object,
        default: null,
    },
    recipients: {
        type: Array,
        default: () => [],
    },
});

const isEdit = computed(() => props.mode === 'edit');

const form = useForm(defaultAutomatizationForm(props.automatization));

watch(
    () => form.type,
    (type, oldType) => {
        if (!isEdit.value) {
            const currentName = form.name?.trim();
            const oldDefault = oldType ? automatizationTypeLabel(oldType) : '';
            if (!currentName || currentName === oldDefault) {
                form.name = automatizationTypeLabel(type);
            }
        }

        if (type === 'invoice_report') {
            form.recipient_id = '';
        }
        if (type !== 'invoice_due_reminder') {
            form.due_offset_days = '';
        } else {
            form.date_trigger = todayYMD();
        }
    }
);

const recipientLabel = (r) => r.company_name || r.name;

const inputClass =
    'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500';

function submit() {
    if (isEdit.value) {
        form.patch(route('automatizations.update', props.automatization.id));
        return;
    }

    form.post(route('automatizations.store'));
}

watch(
    () => Number(form.item_count) || 0,
    (count) => {
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
                <header>
                    <h2 class="text-lg font-medium text-gray-900">Nastavenia automatizácie</h2>
                    <!-- <p class="mt-1 text-sm text-gray-600">
                        Nastavte automatické generovanie faktúr alebo mesačný report.
                    </p> -->
                </header>

                <div class="mt-6 space-y-6">
                    <div>
                        <InputLabel for="auto-name" value="Názov" />
                        <input
                            id="auto-name"
                            type="text"
                            v-model="form.name"
                            :class="inputClass"
                            maxlength="255"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div v-if="form.type === 'invoice_auto_gen'">
                        <InputLabel for="auto-item_count" value="Počet položiek vo faktúre" />
                        <input
                            id="auto-item_count"
                            type="number"
                            v-model="form.item_count"
                            :class="inputClass"
                            maxlength="255"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div v-if="form.type === 'invoice_auto_gen' && form.item_count > 0">
                        <InputLabel for="auto-item_count" value="Názov položiek" />

                            <div v-for="(name, index) in form.item_names" :key="index">
                                <input
                                    :id="`auto-item_name-${index}`"
                                    type="text"
                                    v-model="form.item_names[index]"
                                    :class="inputClass"
                                    maxlength="255"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.item_name" />
                            </div>
                    </div>

                    <div>
                        <InputLabel for="auto-type" value="Typ" />
                        <select
                            id="auto-type"
                            v-model="form.type"
                            :class="inputClass"
                        >
                            <option value="invoice_auto_gen">Automatické generovanie faktúr</option>
                            <option value="invoice_report">Mesačný report faktúr</option>
                            <option value="invoice_due_reminder">Upozornenie na splatnosť</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.type" />
                    </div>

                    <div v-if="form.type === 'invoice_auto_gen'">
                        <InputLabel for="auto-recipient" value="Klient" />
                        <select
                            id="auto-recipient"
                            v-model="form.recipient_id"
                            :class="inputClass"
                        >
                            <option value="">Vyberte klienta</option>
                            <option
                                v-for="r in recipients"
                                :key="r.id"
                                :value="r.id"
                            >
                                {{ recipientLabel(r) }}
                            </option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.recipient_id" />
                    </div>

                    <div v-if="form.type !== 'invoice_due_reminder'">
                        <InputLabel for="auto-trigger" value="Dátum prvého spustenia" />
                        <input
                            id="auto-trigger"
                            type="date"
                            v-model="form.date_trigger"
                            :class="inputClass"
                        />
                        <p v-if="form.type === 'invoice_report'" class="mt-1 text-sm text-gray-500">
                            Report je za predošlý mesiac od tohto dátumu.
                        </p>
                        <InputError class="mt-2" :message="form.errors.date_trigger" />
                    </div>

                    <div v-else>
                        <input type="hidden" v-model="form.date_trigger" />
                        <p class="text-sm text-gray-600">
                            Táto automatizácia sa spúšťa denne od dnešného dňa.
                        </p>
                    </div>

                    <div v-if="form.type === 'invoice_due_reminder'">
                        <InputLabel for="auto-offset" value="Koľko dní pred/po splatnosti" />
                        <input
                            id="auto-offset"
                            type="number"
                            step="1"
                            min="-365"
                            max="365"
                            v-model="form.due_offset_days"
                            :class="inputClass"
                        />
                        <p class="mt-1 text-sm text-gray-500">
                            - pred splatnosťou <br />
                            +  po splatnosti <br />
                            0 v deň splatnosti
                        </p>
                        <InputError class="mt-2" :message="form.errors.due_offset_days" />
                    </div>

                    <div v-if="isEdit" class="flex items-center gap-2">
                        <input
                            id="auto-active"
                            type="checkbox"
                            v-model="form.is_active"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                        />
                        <InputLabel for="auto-active" value="Aktívne" />
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
                {{ form.processing ? 'Ukladám...' : (isEdit ? 'Uložiť' : 'Vytvoriť automatizáciu') }}
            </button>
        </div>
    </form>
</template>
