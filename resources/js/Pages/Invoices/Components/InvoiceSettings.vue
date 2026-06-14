<script setup lang="ts">
import { computed } from 'vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { prefixedId } from '@/Pages/Invoices/Utils/helpers';
import type { AuthUser } from '@/types/inertia';

interface InvoiceSettingsForm {
    company_logo: File | null
    errors: Record<string, string | undefined>
}

interface Props {
    mode?: 'profile' | 'invoice'
    form?: InvoiceSettingsForm | null
    user?: AuthUser | null
    idPrefix?: string
}

const props = withDefaults(defineProps<Props>(), {
    mode: 'profile',
    form: null,
    user: null,
    idPrefix: 'invoice-settings',
});

const companyLogoPreviewUrl = computed(() => {
    if (props.mode === 'profile' && props.form?.company_logo && isFile(props.form.company_logo)) {
        return URL.createObjectURL(props.form.company_logo);
    }
    return props.user?.company_logo?.url ?? null;
});

function isFile(value: unknown): value is File {
    return typeof File !== 'undefined' && value instanceof File;
}

const id = (name: string): string => prefixedId(props.idPrefix, name);
</script>

<template>
    <div class="space-y-2">
        <InputLabel :for="id('company_logo')" value="Logo firmy" />
        <div class="flex items-center gap-4">
            <img
                v-if="companyLogoPreviewUrl"
                :src="companyLogoPreviewUrl"
                :alt="mode === 'profile' && form?.company_logo && isFile(form.company_logo) ? 'Náhľad nového loga' : 'Logo firmy'"
                class="h-16 w-16 rounded border border-gray-200 object-contain bg-gray-50"
            >
            <input
                v-if="mode === 'profile' && form"
                :id="id('company_logo')"
                type="file"
                accept="image/*"
                class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-md file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-emerald-700 hover:file:bg-emerald-100"
                @input="form!.company_logo = ($event.target as HTMLInputElement).files?.[0] ?? null"
            >
            <p v-else-if="mode === 'invoice' && !companyLogoPreviewUrl" class="text-sm text-gray-500">
                Žiadne logo. Pridajte ho v profile v sekcii Nastavenia faktúry.
            </p>
        </div>
        <InputError v-if="form" class="mt-2" :message="form.errors?.company_logo" />
    </div>
</template>
