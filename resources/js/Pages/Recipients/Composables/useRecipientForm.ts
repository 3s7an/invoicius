import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { createRecipientFormDefaults, recipientPayload } from '../Utils/recipientFormDefaults';
import type { RecipientFormData } from '../Utils/recipientFormDefaults';
import type { RecipientResource } from '@/types';

interface RecipientFormProps {
    mode?: 'create' | 'edit'
    recipient?: RecipientResource | null
    fromInvoice?: boolean
}

export function useRecipientForm(props: RecipientFormProps) {
    const { t } = useI18n();
    const isEdit = computed(() => props.mode === 'edit');
    const sourceRecipient = computed(() => props.recipient ?? ({} as RecipientResource));

    const form = useForm<RecipientFormData>(
        createRecipientFormDefaults({
            mode: props.mode,
            recipient: props.recipient,
            fromInvoice: props.fromInvoice,
        })
    );

    const heading = computed(() => (isEdit.value ? t('recipients.modal.editTitle') : t('recipients.modal.newTitle')));
    const submitLabel = computed(() => (isEdit.value ? t('recipients.form.saveClient') : t('recipients.form.createClient')));
    const processingLabel = computed(() => (isEdit.value ? t('common.saving') : t('common.creating')));

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
