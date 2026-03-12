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
    return r.company_name || r.name || `Recipient #${r.id}`;
}

const selectClass =
    'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500';
</script>

<template>
    <Modal :show="show" @close="emit('close')" max-width="lg">
        <form @submit.prevent="submit" class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                {{ editingAutomatization ? 'Edit automatization' : 'New automatization' }}
            </h2>

            <div class="mt-6 space-y-4">
                <div>
                    <InputLabel for="auto-recipient" value="Recipient" />
                    <select
                        id="auto-recipient"
                        v-model="form.recipient_id"
                        :class="selectClass"
                    >
                        <option value="">Select recipient</option>
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
                    <InputLabel for="auto-type" value="Type" />
                    <select
                        id="auto-type"
                        v-model="form.type"
                        :class="selectClass"
                    >
                        <option value="invoice_auto_gen">Invoice auto-generation</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.type" />
                </div>

                <div>
                    <InputLabel for="auto-trigger" value="First trigger date" />
                    <input
                        id="auto-trigger"
                        type="date"
                        v-model="form.date_trigger"
                        :class="selectClass"
                    />
                    <InputError class="mt-2" :message="form.errors.date_trigger" />
                </div>

                <div v-if="editingAutomatization" class="flex items-center gap-2">
                    <input
                        id="auto-active"
                        type="checkbox"
                        v-model="form.is_active"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                    />
                    <InputLabel for="auto-active" value="Active" />
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <SecondaryButton @click="emit('close')">Cancel</SecondaryButton>
                <PrimaryButton :disabled="form.processing">
                    {{ editingAutomatization ? 'Update' : 'Create' }}
                </PrimaryButton>
            </div>
        </form>
    </Modal>
</template>
