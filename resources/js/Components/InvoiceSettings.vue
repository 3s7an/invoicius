<script setup>
import { computed } from 'vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';

const props = defineProps({
    /** 'profile' = editable (form), 'invoice' = display/select (model) */
    mode: {
        type: String,
        default: 'profile',
        validator: (v) => ['profile', 'invoice'].includes(v),
    },
    /** In profile mode: form object with company_logo, invoice_color_id, errors */
    form: {
        type: Object,
        default: null,
    },
    /** In invoice mode: model with invoice_color_id (e.g. invoice reactive) */
    model: {
        type: Object,
        default: null,
    },
    /** User for logo URL (auth.user.company_logo.url from HandleInertiaRequests) */
    user: {
        type: Object,
        default: null,
    },
    invoiceColors: {
        type: Array,
        default: () => [],
    },
    /** Alias for invoiceColors (Inertia may pass snake_case) */
    invoice_colors: {
        type: Array,
        default: () => [],
    },
    idPrefix: {
        type: String,
        default: 'invoice-settings',
    },
});

const colorsList = computed(() => {
    const list = props.invoiceColors ?? props.invoice_colors ?? [];
    return Array.isArray(list) ? list : [];
});

const invoiceColorId = computed({
    get() {
        if (props.mode === 'profile' && props.form) return props.form.invoice_color_id ?? '';
        if (props.mode === 'invoice' && props.model) return props.model.invoice_color_id ?? '';
        return '';
    },
    set(val) {
        if (props.mode === 'profile' && props.form) props.form.invoice_color_id = val;
        if (props.mode === 'invoice' && props.model) props.model.invoice_color_id = val;
    },
});

const companyLogoPreviewUrl = computed(() => {
    if (props.mode === 'profile' && props.form?.company_logo && isFile(props.form.company_logo)) {
        return URL.createObjectURL(props.form.company_logo);
    }
    return props.user?.company_logo?.url ?? null;
});

const selectedColor = computed(() => {
    const id = Number(invoiceColorId.value);
    if (!id) return null;
    return colorsList.value.find((c) => Number(c.id) === id) ?? null;
});

function isFile(value) {
    return typeof File !== 'undefined' && value instanceof File;
}

function id(name) {
    return `${props.idPrefix}-${name}`;
}
</script>

<template>
    <div class="space-y-6">
        <!-- Company logo -->
        <div class="space-y-2">
            <InputLabel :for="id('company_logo')" value="Company logo" />
            <div class="flex items-center gap-4">
                <img
                    v-if="companyLogoPreviewUrl"
                    :src="companyLogoPreviewUrl"
                    :alt="mode === 'profile' && form?.company_logo && isFile(form.company_logo) ? 'New logo preview' : 'Company logo'"
                    class="h-16 w-16 rounded border border-gray-200 object-contain bg-gray-50"
                />
                <input
                    v-if="mode === 'profile' && form"
                    :id="id('company_logo')"
                    type="file"
                    accept="image/*"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-md file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-indigo-700 hover:file:bg-indigo-100"
                    @input="form.company_logo = $event.target.files?.[0] ?? null"
                />
                <p v-else-if="mode === 'invoice' && !companyLogoPreviewUrl" class="text-sm text-gray-500">
                    No logo set. Add one in Profile.
                </p>
            </div>
            <InputError v-if="form" class="mt-2" :message="form.errors?.company_logo" />
        </div>

        <!-- Invoice color -->
        <div class="space-y-2">
            <InputLabel :for="id('invoice_color_id')" value="Invoice color" />
            <div v-if="colorsList.length" class="flex flex-wrap items-center gap-2.5" role="group" :aria-label="id('invoice_color_id')">
                <button
                    v-for="c in colorsList"
                    :key="c.id"
                    type="button"
                    :title="c.name"
                    class="h-5 w-5 shrink-0 rounded-full border-2 transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    :class="Number(invoiceColorId) === Number(c.id) ? 'border-gray-800 ring-2 ring-gray-300 ring-offset-1' : 'border-transparent hover:border-gray-300'"
                    :style="{ backgroundColor: c.hex }"
                    @click="invoiceColorId = c.id"
                />
            </div>
            <p v-else class="text-sm text-gray-500">
                No colors. Run: <code class="rounded bg-gray-100 px-1">php artisan db:seed --class=InvoiceColorSeeder</code>
            </p>
            <p v-if="selectedColor" class="text-sm text-gray-500">
                {{ selectedColor.name }}
            </p>
            <InputError v-if="form" class="mt-2" :message="form.errors?.invoice_color_id" />
        </div>
    </div>
</template>
