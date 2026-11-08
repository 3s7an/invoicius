<script setup lang="ts">
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    canResetPassword?: boolean
    status?: string
}>();

const form = useForm<{ email: string; password: string; remember: boolean }>({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout
        :eyebrow="t('auth.login.title')"
        :title="t('auth.login.heading')"
        :lead="t('auth.login.lead')"
    >
        <Head :title="t('auth.login.title')" />

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

            <div class="g-field">
                <InputLabel for="password" :value="t('common.password')" />
                <TextInput
                    id="password"
                    type="password"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />
                <InputError :message="form.errors.password" />
            </div>

            <div class="g-row-between">
                <label class="g-check">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span>{{ t('auth.login.rememberMe') }}</span>
                </label>

                <Link v-if="canResetPassword" :href="route('password.request')">
                    {{ t('auth.login.forgotPassword') }}
                </Link>
            </div>

            <button type="submit" class="g-pill g-pill--block" :disabled="form.processing">
                {{ t('auth.login.submit') }}
                <span class="g-knob"><i class="pi pi-arrow-right" /></span>
            </button>
        </form>

        <template #footer>
            {{ t('auth.login.noAccount') }}
            <Link :href="route('register')">{{ t('auth.login.registerLink') }}</Link>
        </template>
    </GuestLayout>
</template>
