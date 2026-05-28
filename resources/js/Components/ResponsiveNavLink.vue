<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'block w-full rounded-lg border-l-4 border-white bg-white/15 py-2.5 ps-3 pe-4 text-start text-base font-semibold text-white focus:outline-none transition duration-150 ease-in-out'
        : 'block w-full rounded-lg border-l-4 border-transparent py-2.5 ps-3 pe-4 text-start text-base font-semibold text-white/90 hover:border-white/50 hover:bg-white/10 hover:text-white focus:outline-none transition duration-150 ease-in-out',
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
