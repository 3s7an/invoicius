<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0"
    >
        <header class="flex items-center gap-2">
            <Link :href="route('login')" class="flex items-center gap-2 no-underline">
                <svg
                    class="h-10 w-10 shrink-0 fill-current text-gray-600"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                    aria-hidden="true"
                >
                    <path
                        d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm0 2v5h5l-5-5zm-4 4h8v1.5h-8V8zm0 3h8v1.5h-8V11zm0 3h5v1.5h-5V14z"
                    />
                </svg>
                <span class="text-xl font-light tracking-tight text-gray-700">invoicer</span>
            </Link>
        </header>

        <div
            class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>
