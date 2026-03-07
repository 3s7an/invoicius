<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import RecipientDetailsForm from '@/Components/RecipientDetailsForm.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    from_invoice: {
        type: Boolean,
        default: false,
    },
});

const form = useForm({
    name: '',
    company_name: '',
    street: '',
    street_num: '',
    city: '',
    zip: '',
    state: '',
    ico: '',
    dic: '',
    ic_dph: '',
    iban: '',
    from_invoice: props.from_invoice,
});
</script>

<template>
    <Head title="New recipient" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-wrap items-center gap-3">
                <Link :href="route('recipients.index')" class="text-sm text-gray-500 hover:text-gray-700">← Recipients</Link>
                <h1 class="text-lg font-medium text-gray-900">New recipient</h1>
            </div>
                <form @submit.prevent="form.post(route('recipients.store'))" class="space-y-6 rounded-lg bg-white p-6 shadow">
                    <RecipientDetailsForm
                        mode="recipient"
                        :model-value="form"
                        :errors="form.errors"
                        id-prefix="recipient"
                    />
                    <div class="flex gap-4">
                        <PrimaryButton :disabled="form.processing">Create recipient</PrimaryButton>
                        <Link :href="route('recipients.index')" class="inline-flex items-center rounded-md px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Cancel
                        </Link>
                    </div>
                </form>
        </div>
    </AuthenticatedLayout>
</template>
