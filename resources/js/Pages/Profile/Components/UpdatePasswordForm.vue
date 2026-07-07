<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{ t('profile.password.title') }}</h2>
            <p class="mt-1 text-sm text-gray-600">{{ t('profile.password.description') }}</p>
        </header>

        <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
            <div>
                <InputLabel for="current_password" :value="t('profile.password.current')" />
                <div class="relative mt-1">
                    <i class="pi pi-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm" aria-hidden="true" />
                    <TextInput
                        id="current_password"
                        ref="currentPasswordInput"
                        v-model="form.current_password"
                        type="password"
                        class="block w-full pl-9"
                        autocomplete="current-password"
                    />
                </div>
                <InputError :message="form.errors.current_password" class="mt-2" />
            </div>

            <div>
                <InputLabel for="password" :value="t('profile.password.new')" />
                <div class="relative mt-1">
                    <i class="pi pi-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm" aria-hidden="true" />
                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="block w-full pl-9"
                        autocomplete="new-password"
                    />
                </div>
                <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <div>
                <InputLabel for="password_confirmation" :value="t('common.passwordConfirmation')" />
                <div class="relative mt-1">
                    <i class="pi pi-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm" aria-hidden="true" />
                    <TextInput
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        class="block w-full pl-9"
                        autocomplete="new-password"
                    />
                </div>
                <InputError :message="form.errors.password_confirmation" class="mt-2" />
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
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const passwordInput = ref<{ focus: () => void } | null>(null);
const currentPasswordInput = ref<{ focus: () => void } | null>(null);

const form = useForm<{ current_password: string; password: string; password_confirmation: string }>({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = (): void => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value?.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value?.focus();
            }
        },
    });
};
</script>
