<script setup lang="ts">
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

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
    <GuestLayout
        :eyebrow="t('auth.verifyEmail.eyebrow')"
        :title="t('auth.verifyEmail.title')"
        :lead="t('auth.verifyEmail.description')"
    >
        <Head :title="t('auth.verifyEmail.title')" />

        <div v-if="verificationLinkSent" class="g-note">
            {{ t('auth.verifyEmail.linkSent') }}
        </div>

        <form class="g-form" @submit.prevent="submit">
            <button type="submit" class="g-pill g-pill--block" :disabled="form.processing">
                {{ t('auth.verifyEmail.submit') }}
                <span class="g-knob"><i class="pi pi-arrow-right" /></span>
            </button>
        </form>

        <template #footer>
            <Link :href="route('logout')" method="post" as="button" class="g-text-link">
                {{ t('nav.logout') }}
            </Link>
        </template>
    </GuestLayout>
</template>
