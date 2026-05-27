import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { createRecipientFormDefaults, recipientPayload } from '../Utils/recipientFormDefaults';

export function useRecipientForm(props) {
    const isEdit = computed(() => props.mode === 'edit');
    const sourceRecipient = computed(() => props.recipient ?? {});

    const form = useForm(
        createRecipientFormDefaults({
            mode: props.mode,
            recipient: props.recipient,
            fromInvoice: props.fromInvoice,
        })
    );

    const heading = computed(() => (isEdit.value ? 'Upraviť odberateľa' : 'Nový odberateľ'));
    const submitLabel = computed(() => (isEdit.value ? 'Uložiť odberateľa' : 'Vytvoriť odberateľa'));
    const processingLabel = computed(() => (isEdit.value ? 'Ukladám...' : 'Vytváram...'));

    function submit() {
        const request = form.transform(recipientPayload);

        if (isEdit.value) {
            request.put(route('recipients.update', sourceRecipient.value.id), { preserveScroll: true });
            return;
        }

        request.post(route('recipients.store'), { preserveScroll: true });
    }

    return {
        form,
        heading,
        submitLabel,
        processingLabel,
        submit,
    };
}

