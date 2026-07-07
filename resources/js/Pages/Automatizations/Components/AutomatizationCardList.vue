<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import { formatDate } from '@/utils/formatters';
import Button from 'primevue/button';
import { useAutomatizationActions } from '@/Pages/Automatizations/Composables/automatizationActions';
import type { Automatization } from '../Utils/types';

const { t } = useI18n();
const { toggleActive, confirmDelete } = useAutomatizationActions();

defineProps<{
    automatizations: Automatization[]
}>();

const emit = defineEmits<{ edit: [Automatization] }>();
</script>

<template>
    <div class="space-y-3">
        <article
            v-for="automatization in automatizations"
            :key="automatization.id"
            class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm"
        >
            <div class="flex items-start justify-between gap-3">
                <p class="text-base font-semibold text-gray-900">{{ automatization.type_label }}</p>
                <span
                    class="inline-flex shrink-0 items-center rounded-full px-2 py-0.5 text-xs font-medium"
                    :class="automatization.is_active
                        ? 'bg-green-100 text-green-700'
                        : 'bg-gray-100 text-gray-500'"
                >
                    {{ automatization.is_active ? t('automatizations.list.active') : t('automatizations.list.inactive') }}
                </span>
            </div>

            <dl class="mt-3 text-sm">
                <div class="flex justify-between gap-4">
                    <dt class="text-gray-500">{{ t('automatizations.list.nextRun') }}</dt>
                    <dd class="font-medium text-gray-900">{{ formatDate(automatization.date_trigger) }}</dd>
                </div>
            </dl>

            <div class="mt-4 flex items-center justify-end gap-1 border-t border-gray-100 pt-3">
                <Button
                    :icon="automatization.is_active ? 'pi pi-pause' : 'pi pi-play'"
                    class="p-button-text p-button-sm"
                    :title="automatization.is_active ? t('automatizations.list.deactivate') : t('automatizations.list.activate')"
                    @click="toggleActive(automatization)"
                />
                <Button
                    icon="pi pi-pencil"
                    class="p-button-text p-button-sm"
                    :title="t('common.edit')"
                    @click="emit('edit', automatization)"
                />
                <Button
                    icon="pi pi-trash"
                    class="p-button-text p-button-sm p-button-danger"
                    :title="t('common.delete')"
                    @click="confirmDelete(automatization)"
                />
            </div>
        </article>
    </div>
</template>
