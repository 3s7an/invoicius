<script setup lang="ts">
import { onMounted, onUnmounted, watch } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps<{
    show: boolean
    title: string
    badge?: string
    subtitle?: string
}>();

const emit = defineEmits<{ close: [] }>();

function close() {
    emit('close');
}

function handleEscape(e: KeyboardEvent) {
    if (e.key === 'Escape' && props.show) {
        e.preventDefault();
        close();
    }
}

watch(
    () => props.show,
    (show) => {
        document.body.style.overflow = show ? 'hidden' : '';
    },
    { immediate: true },
);

onMounted(() => document.addEventListener('keydown', handleEscape));
onUnmounted(() => {
    document.removeEventListener('keydown', handleEscape);
    document.body.style.overflow = '';
});
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="show"
                class="fixed inset-0 z-40 bg-gray-900/45"
                @click="close"
            />
        </Transition>

        <Transition
            enter-active-class="duration-300 ease-out"
            enter-from-class="translate-x-full"
            enter-to-class="translate-x-0"
            leave-active-class="duration-200 ease-in"
            leave-from-class="translate-x-0"
            leave-to-class="translate-x-full"
        >
            <div
                v-if="show"
                class="fixed bottom-0 right-0 top-0 z-50 flex w-full transform flex-col bg-white sm:max-w-[920px]"
                style="box-shadow: -4px 0 24px rgba(0,0,0,.12);"
            >
                <div class="flex shrink-0 items-center gap-3 border-b border-gray-200 px-7 py-4">
                    <div class="flex min-w-0 flex-1 items-center gap-2.5">
                        <h2 class="text-xl font-semibold text-gray-900">{{ title }}</h2>
                        <span v-if="badge" class="tabular-nums text-sm text-gray-400">{{ badge }}</span>
                    </div>
                    <button
                        type="button"
                        class="shrink-0 rounded p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600"
                        @click="close"
                    >
                        <i class="pi pi-times text-xl" aria-hidden="true" />
                        <span class="sr-only">{{ t('common.close') }}</span>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto px-7 pb-7 pt-1">
                    <slot />
                </div>

                <div v-if="$slots.footer" class="shrink-0 border-t border-gray-200 bg-gray-50 px-7 py-3.5">
                    <slot name="footer" />
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
