<script setup lang="ts">
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{ status?: string }>();

const form = useForm<Record<string, never>>({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <GuestLayout>
        <Head title="Overenie e-mailu" />

        <div class="mb-4 text-sm text-gray-600">
            Ďakujeme za registráciu! Pred začatím prosím overte svoju e-mailovú
            adresu kliknutím na odkaz v e-maile, ktorý sme vám práve poslali. Ak ste
            e-mail nedostali, radi vám pošleme ďalší.
        </div>

        <div
            class="mb-4 text-sm font-medium text-green-600"
            v-if="verificationLinkSent"
        >
            Nový overovací odkaz bol odoslaný na e-mailovú adresu zadanú pri registrácii.
        </div>

        <form @submit.prevent="submit">
            <div class="mt-4 flex items-center justify-between">
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Znova poslať overovací e-mail
                </PrimaryButton>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Odhlásiť sa
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
