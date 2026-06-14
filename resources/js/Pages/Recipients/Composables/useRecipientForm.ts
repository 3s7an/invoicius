import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { createRecipientFormDefaults, recipientPayload } from '../Utils/recipientFormDefaults';
import type { RecipientFormData } from '../Utils/recipientFormDefaults';
import type { RecipientResource } from '@/types';

interface RecipientFormProps {
    mode?: 'create' | 'edit'
    recipient?: RecipientResource | null
    fromInvoice?: boolean
}

export function useRecipientForm(props: RecipientFormProps) {
    const isEdit = computed(() => props.mode === 'edit');
    const sourceRecipient = computed(() => props.recipient ?? ({} as RecipientResource));

    const form = useForm<RecipientFormData>(
        createRecipientFormDefaults({
            mode: props.mode,
            recipient: props.recipient,
            fromInvoice: props.fromInvoice,
        })
    );

    const heading = computed(() => (isEdit.value ? 'Upraviť klienta' : 'Nový klient'));
    const submitLabel = computed(() => (isEdit.value ? 'Uložiť klienta' : 'Vytvoriť klienta'));
    const processingLabel = computed(() => (isEdit.value ? 'Ukladám...' : 'Vytváram...'));

    function submit(): void {
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
