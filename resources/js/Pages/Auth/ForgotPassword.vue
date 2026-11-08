<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{ status?: string }>();

const form = useForm<{ email: string }>({ email: '' });

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout
        :eyebrow="t('auth.forgotPassword.eyebrow')"
        :title="t('auth.forgotPassword.title')"
        :lead="t('auth.forgotPassword.description')"
    >
        <Head :title="t('auth.forgotPassword.title')" />

        <div v-if="status" class="g-note">
            {{ status }}
        </div>

        <form class="g-form" @submit.prevent="submit">
            <div class="g-field">
                <InputLabel for="email" :value="t('common.email')" />
                <TextInput
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError :message="form.errors.email" />
            </div>

            <button type="submit" class="g-pill g-pill--block" :disabled="form.processing">
                {{ t('auth.forgotPassword.submit') }}
                <span class="g-knob"><i class="pi pi-arrow-right" /></span>
            </button>
        </form>

        <template #footer>
            <Link :href="route('login')">{{ t('auth.forgotPassword.backToLogin') }}</Link>
        </template>
    </GuestLayout>
</template>
