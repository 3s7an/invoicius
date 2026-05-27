<template>
    <form @submit.prevent="onSubmit">
        <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
            <h2 class="text-lg font-medium text-gray-900">Nastavenia faktúry</h2>
            <p class="mt-1 text-sm text-gray-600">Logo zobrazené na PDF faktúrach.</p>

            <div class="mt-6">
                <InvoiceSettings mode="profile" :form="form" :user="user" id-prefix="profile-invoice" />
            </div>

            <div class="mt-6 flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Uložiť</PrimaryButton>
                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Uložené.</p>
                </Transition>
            </div>
        </div>
    </form>
</template>

<script setup>
import InvoiceSettings from '@/Pages/Invoices/Components/InvoiceSettings.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

defineProps({
    form: {
        type: Object,
        required: true,
    },
    user: {
        type: Object,
        default: null,
    },
    onSubmit: {
        type: Function,
        required: true,
    },
});
</script>


