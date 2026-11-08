<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <Link :href="route('home')" class="brand">
        <span class="brand-mark">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path
                    d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm0 2v5h5l-5-5zm-4 4h8v1.5h-8V8zm0 3h8v1.5h-8V11zm0 3h5v1.5h-5V14z"
                />
            </svg>
        </span>
        invoicius
    </Link>
</template>

<style scoped>
.brand {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    text-decoration: none;
    color: var(--g-ink);
    font: 600 18px/1 var(--g-font);
    letter-spacing: -0.03em;
}
.brand-mark {
    width: 26px;
    height: 26px;
    border-radius: 8px;
    display: grid;
    place-items: center;
    background: var(--g-accent);
    color: #ffffff;
}
.brand-mark svg {
    width: 15px;
    height: 15px;
    fill: currentColor;
}
</style>
