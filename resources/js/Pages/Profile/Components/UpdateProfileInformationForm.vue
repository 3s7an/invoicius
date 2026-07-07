<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{ t('profile.info.title') }}</h2>
            <p class="mt-1 text-sm text-gray-600">{{ t('profile.info.description') }}</p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="mt-6 space-y-6">
            <div>
                <InputLabel for="name" :value="t('common.name')" />
                <div class="relative mt-1">
                    <i class="pi pi-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm" aria-hidden="true" />
                    <TextInput
                        id="name"
                        type="text"
                        class="block w-full pl-9"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                    />
                </div>
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" :value="t('common.email')" />
                <div class="relative mt-1">
                    <i class="pi pi-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm" aria-hidden="true" />
                    <TextInput
                        id="email"
                        type="email"
                        class="block w-full pl-9"
                        v-model="form.email"
                        required
                        autocomplete="username"
                    />
                </div>
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-2 text-sm text-gray-800">
                    {{ t('profile.info.emailUnverified') }}
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                    >
                        {{ t('profile.info.resendVerification') }}
                    </Link>
                </p>

                <div v-show="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">
                    {{ t('profile.info.verificationSent') }}
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">{{ t('common.save') }}</PrimaryButton>
                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">{{ t('common.saved') }}</p>
                </Transition>
            </div>
        </form>
    </section>
</template>



<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import type { AuthUser } from '@/types/inertia';

const { t } = useI18n();

defineProps<{
    mustVerifyEmail?: boolean
    status?: string
}>();

const user = (usePage().props as unknown as { auth: { user: AuthUser } }).auth.user;

const form = useForm<{ name: string; email: string }>({
    name: user.name,
    email: user.email,
});
</script>

