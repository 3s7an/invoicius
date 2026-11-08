<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const form = useForm<{ password: string }>({ password: '' });

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <GuestLayout
        :eyebrow="t('auth.confirmPassword.eyebrow')"
        :title="t('auth.confirmPassword.title')"
        :lead="t('auth.confirmPassword.description')"
    >
        <Head :title="t('auth.confirmPassword.title')" />

        <form class="g-form" @submit.prevent="submit">
            <div class="g-field">
                <InputLabel for="password" :value="t('common.password')" />
                <TextInput
                    id="password"
                    type="password"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    autofocus
                />
                <InputError :message="form.errors.password" />
            </div>

            <button type="submit" class="g-pill g-pill--block" :disabled="form.processing">
                {{ t('auth.confirmPassword.submit') }}
                <span class="g-knob"><i class="pi pi-arrow-right" /></span>
            </button>
        </form>
    </GuestLayout>
</template>
