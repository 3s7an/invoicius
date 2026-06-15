<script setup lang="ts">
import { computed } from 'vue';
import InputError from '@/Components/InputError.vue';
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

const hasLogo = computed(() => !!companyLogoPreviewUrl.value);

function isFile(value: unknown): value is File {
    return typeof File !== 'undefined' && value instanceof File;
}

function clearLogo() {
    // eslint-disable-next-line vue/no-mutating-props
    if (props.form) props.form.company_logo = null;
}

const id = (name: string): string => prefixedId(props.idPrefix, name);
</script>

<template>
    <!-- eslint-disable vue/no-mutating-props -->
    <!-- Profile mode: design system dashed-box uploader -->
    <div v-if="mode === 'profile' && form">
        <div class="flex items-center gap-5">
            <label :for="id('company_logo')" class="cursor-pointer shrink-0">
                <div
                    class="flex items-center justify-center overflow-hidden rounded-xl transition-colors"
                    style="width:100px; height:76px; border-width:2px; border-style:dashed;"
                    :style="hasLogo
                        ? 'border-color:var(--primary); background:transparent;'
                        : 'border-color:var(--border-strong); background:var(--surface-muted);'"
                >
                    <img
                        v-if="companyLogoPreviewUrl"
                        :src="companyLogoPreviewUrl"
                        alt="Logo firmy"
                        style="width:100%; height:100%; object-fit:contain;"
                    >
                    <i v-else class="pi pi-image" style="font-size:28px; color:var(--text-faint);" aria-hidden="true" />
                </div>
                <input
                    :id="id('company_logo')"
                    type="file"
                    accept="image/*"
                    class="sr-only"
                    @input="form!.company_logo = ($event.target as HTMLInputElement).files?.[0] ?? null"
                >
            </label>

            <div>
                <p style="margin:0; font-size:14px; font-weight:500; color:var(--text-strong);">
                    {{ hasLogo ? 'Logo nahraté' : 'Žiadne logo' }}
                </p>
                <p style="margin:4px 0 8px; font-size:13px; color:var(--text-muted);">
                    Kliknite na plochu a vyberte súbor.
                </p>
                <button
                    v-if="hasLogo && form?.company_logo"
                    type="button"
                    style="background:none; border:none; cursor:pointer; font-size:13px; color:var(--danger); padding:0;"
                    @click="clearLogo"
                >
                    Odstrániť logo
                </button>
            </div>
        </div>
        <InputError class="mt-2" :message="form.errors?.company_logo" />
    </div>

    <!-- Invoice mode: unchanged -->
    <div v-else class="space-y-2">
        <div class="flex items-center gap-4">
            <img
                v-if="companyLogoPreviewUrl"
                :src="companyLogoPreviewUrl"
                alt="Logo firmy"
                class="h-16 w-16 rounded border border-gray-200 object-contain bg-gray-50"
            >
            <p v-else class="text-sm text-gray-500">
                Žiadne logo. Pridajte ho v profile v sekcii Nastavenia faktúry.
            </p>
        </div>
    </div>
</template>
