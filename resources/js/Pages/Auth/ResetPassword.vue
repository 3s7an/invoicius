<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps<{
    email: string
    token: string
}>();

const form = useForm<{ token: string; email: string; password: string; password_confirmation: string }>({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout
        :eyebrow="t('auth.resetPassword.eyebrow')"
        :title="t('auth.resetPassword.title')"
        :lead="t('auth.resetPassword.description')"
    >
        <Head :title="t('auth.resetPassword.title')" />

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

            <div class="g-field">
                <InputLabel for="password" :value="t('common.password')" />
                <TextInput
                    id="password"
                    type="password"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />
                <InputError :message="form.errors.password" />
            </div>

            <div class="g-field">
                <InputLabel for="password_confirmation" :value="t('common.passwordConfirmation')" />
                <TextInput
                    id="password_confirmation"
                    type="password"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />
                <InputError :message="form.errors.password_confirmation" />
            </div>

            <button type="submit" class="g-pill g-pill--block" :disabled="form.processing">
                {{ t('auth.resetPassword.submit') }}
                <span class="g-knob"><i class="pi pi-arrow-right" /></span>
            </button>
        </form>
    </GuestLayout>
</template>
