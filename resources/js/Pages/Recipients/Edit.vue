<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import RecipientDetailsForm from '@/Components/RecipientDetailsForm.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    recipient: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    name: props.recipient.name ?? '',
    company_name: props.recipient.company_name ?? '',
    street: props.recipient.street ?? '',
    street_num: props.recipient.street_num ?? '',
    city: props.recipient.city ?? '',
    zip: props.recipient.zip ?? '',
    state: props.recipient.state ?? '',
    ico: props.recipient.ico ?? '',
    dic: props.recipient.dic ?? '',
    ic_dph: props.recipient.ic_dph ?? '',
    iban: props.recipient.iban ?? '',
});
</script>

<template>
    <Head :title="'Edit: ' + (recipient.company_name || recipient.name || 'Recipient')" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-wrap items-center gap-3">
                <Link :href="route('recipients.index')" class="text-sm text-gray-500 hover:text-gray-700">← Recipients</Link>
                <h1 class="text-lg font-medium text-gray-900">Edit recipient</h1>
            </div>
                <form @submit.prevent="form.put(route('recipients.update', recipient.id))" class="space-y-6 rounded-lg bg-white p-6 shadow">
                    <RecipientDetailsForm
                        mode="recipient"
                        :model-value="form"
                        :errors="form.errors"
                        id-prefix="recipient"
                    />
                    <div class="flex gap-4">
                        <PrimaryButton :disabled="form.processing">Save changes</PrimaryButton>
                        <Link :href="route('recipients.show', recipient.id)" class="inline-flex items-center rounded-md px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Cancel
                        </Link>
                    </div>
                </form>
        </div>
    </AuthenticatedLayout>
</template>
