<script setup>
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    show: Boolean,
    recipients: {
        type: Array,
        default: () => [],
    },
    editingAutomatization: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(['close']);

const form = useForm({
    recipient_id: '',
    type: 'invoice_auto_gen',
    date_trigger: '',
    is_active: true,
});

watch(() => form.type, (type) => {
    if (type === 'invoice_report') {
        form.recipient_id = '';
    }
});

watch(() => props.show, (open) => {
    if (!open) return;

    if (props.editingAutomatization) {
        form.recipient_id = props.editingAutomatization.recipient_id ?? '';
        form.type = props.editingAutomatization.type ?? 'invoice_auto_gen';
        form.date_trigger = props.editingAutomatization.date_trigger
            ? props.editingAutomatization.date_trigger.substring(0, 10)
            : '';
        form.is_active = props.editingAutomatization.is_active ?? true;
    } else {
        form.reset();
        form.type = 'invoice_auto_gen';
        form.is_active = true;
    }
});

function submit() {
    if (props.editingAutomatization) {
        form.patch(route('automatizations.update', props.editingAutomatization.id), {
            preserveScroll: true,
            onSuccess: () => emit('close'),
        });
    } else {
        form.post(route('automatizations.store'), {
            preserveScroll: true,
            onSuccess: () => emit('close'),
        });
    }
}

function recipientLabel(r) {
    return r.company_name || r.name || `Odberateľ #${r.id}`;
}

const selectClass =
    'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500';
</script>

<template>
    <Modal :show="show" @close="emit('close')" max-width="lg">
        <form @submit.prevent="submit" class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                {{ editingAutomatization ? 'Upraviť automatizáciu' : 'Nová automatizácia' }}
            </h2>

            <div class="mt-6 space-y-4">
                <div v-if="form.type === 'invoice_auto_gen'">
                    <InputLabel for="auto-recipient" value="Odberateľ" />
                    <select
                        id="auto-recipient"
                        v-model="form.recipient_id"
                        :class="selectClass"
                    >
                        <option value="">Vyberte odberateľa</option>
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

                <div>
                    <InputLabel for="auto-type" value="Typ" />
                    <select
                        id="auto-type"
                        v-model="form.type"
                        :class="selectClass"
                    >
                        <option value="invoice_auto_gen">Automatické generovanie faktúr</option>
                        <option value="invoice_report">Mesačný report faktúr</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.type" />
                </div>

                <div>
                    <InputLabel for="auto-trigger" value="Dátum prvého spustenia" />
                    <input
                        id="auto-trigger"
                        type="date"
                        v-model="form.date_trigger"
                        :class="selectClass"
                    />
                    <p v-if="form.type === 'invoice_report'" class="mt-1 text-sm text-gray-500">
                        Report sa odošle v tento deň v mesiaci (za predchádzajúci kalendárny mesiac).
                    </p>
                    <InputError class="mt-2" :message="form.errors.date_trigger" />
                </div>

                <div v-if="editingAutomatization" class="flex items-center gap-2">
                    <input
                        id="auto-active"
                        type="checkbox"
                        v-model="form.is_active"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                    />
                    <InputLabel for="auto-active" value="Aktívne" />
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <SecondaryButton @click="emit('close')">Zrušiť</SecondaryButton>
                <PrimaryButton :disabled="form.processing">
                    {{ editingAutomatization ? 'Uložiť' : 'Vytvoriť' }}
                </PrimaryButton>
            </div>
        </form>
    </Modal>
</template>
