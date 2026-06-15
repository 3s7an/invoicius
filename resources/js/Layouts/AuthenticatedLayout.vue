<script setup lang="ts">
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import type { AuthUser } from '@/types/inertia';

interface SharedProps extends Record<string, unknown> {
    auth: { user: AuthUser }
    flash: { success?: string; error?: string }
}

const NAV_ITEMS = [
    { label: 'Prehľad',        icon: 'pi-home',  href: () => route('dashboard'),              active: () => route().current('dashboard') },
    { label: 'Faktúry',        icon: 'pi-file',  href: () => route('invoices'),               active: () => route().current('invoices') },
    { label: 'Klienti',        icon: 'pi-users', href: () => route('recipients.index'),       active: () => route().current('recipients.*') },
    { label: 'Automatizácie',  icon: 'pi-bolt',  href: () => route('automatizations.index'), active: () => route().current('automatizations.*') },
] as const;

const sidebarOpen = ref(false);
const showUserMenu = ref(false);
const userMenuEl = ref<HTMLElement | null>(null);

const page = usePage<SharedProps>();
const flash = computed(() => page.props.flash ?? {});
const showFlash = ref(false);
const user = computed(() => page.props.auth.user);

const initials = computed(() =>
    user.value.name.split(/\s+/).slice(0, 2).map((w: string) => w[0]).join('').toUpperCase()
);

onMounted(() => {
    if (flash.value.success || flash.value.error) {
        showFlash.value = true;
        setTimeout(() => { showFlash.value = false; }, 4000);
    }
    document.addEventListener('click', onDocClick);
});

onUnmounted(() => {
    document.removeEventListener('click', onDocClick);
});

function onDocClick(e: MouseEvent) {
    if (userMenuEl.value && !userMenuEl.value.contains(e.target as Node)) {
        showUserMenu.value = false;
    }
}

watch(() => page.url, () => {
    sidebarOpen.value = false;
    showUserMenu.value = false;
});
</script>

<template>
    <div class="flex min-h-screen bg-gray-100">
<!-- ── Scrim (mobile) ─────────────────────────────────────────── -->
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="sidebarOpen"
                class="fixed inset-0 z-[38] bg-gray-900/45 md:hidden"
                @click="sidebarOpen = false"
            />
        </Transition>

        <!-- ── Sidebar ────────────────────────────────────────────────── -->
        <aside
            class="fixed inset-y-0 left-0 z-[39] flex w-[248px] select-none flex-col bg-white transition-transform duration-200 ease-in-out"
            style="border-right: 1px solid var(--border)"
            :class="sidebarOpen ? 'translate-x-0 shadow-lg' : '-translate-x-[260px] md:translate-x-0'"
        >
            <!-- Logo -->
            <div class="flex shrink-0 items-center gap-2.5 px-5 pb-5 pt-[22px]" style="border-bottom: 1px solid var(--border-subtle)">
                <svg class="h-7 w-7 shrink-0 fill-current text-emerald-500" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm0 2v5h5l-5-5zm-4 4h8v1.5h-8V8zm0 3h8v1.5h-8V11zm0 3h5v1.5h-5V14z" />
                </svg>
                <span class="text-[17px] font-semibold tracking-tight text-gray-900">Invoicius</span>
            </div>

            <!-- Nav -->
            <nav class="flex flex-1 flex-col gap-0.5 overflow-y-auto p-2.5">
                <Link
                    v-for="item in NAV_ITEMS"
                    :key="item.label"
                    :href="item.href()"
                    class="flex items-center gap-2.5 rounded-md px-3 py-[9px] text-sm transition-colors duration-150"
                    :class="item.active()
                        ? 'bg-emerald-50 font-semibold text-emerald-700'
                        : 'font-medium text-gray-700 hover:bg-gray-50'"
                >
                    <i :class="`pi ${item.icon} text-base`" class="w-5 shrink-0 text-center" aria-hidden="true" />
                    {{ item.label }}
                </Link>
            </nav>

            <!-- User -->
            <div ref="userMenuEl" class="relative shrink-0 p-2.5" style="border-top: 1px solid var(--border-subtle)">
                <!-- Popover -->
                <Transition
                    enter-active-class="transition ease-out duration-100"
                    enter-from-class="opacity-0 scale-95"
                    enter-to-class="opacity-100 scale-100"
                    leave-active-class="transition ease-in duration-75"
                    leave-from-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-95"
                >
                    <div
                        v-if="showUserMenu"
                        class="absolute bottom-[calc(100%+4px)] left-2.5 right-2.5 z-40 rounded-md border border-gray-200 bg-white p-1.5 shadow-md"
                    >
                        <Link
                            :href="route('profile.edit')"
                            class="flex w-full items-center gap-2 rounded px-2.5 py-[7px] text-sm text-gray-700 hover:bg-gray-50"
                        >
                            <i class="pi pi-user text-[13px]" aria-hidden="true" />
                            Profil
                        </Link>
                        <div class="my-1 h-px bg-gray-100" />
                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="flex w-full items-center gap-2 rounded px-2.5 py-[7px] text-sm text-red-600 hover:bg-red-50"
                        >
                            <i class="pi pi-sign-out text-[13px]" aria-hidden="true" />
                            Odhlásiť sa
                        </Link>
                    </div>
                </Transition>

                <!-- User button -->
                <button
                    type="button"
                    class="flex w-full items-center gap-2.5 rounded-md px-2.5 py-2 text-left transition-colors duration-150 hover:bg-gray-50"
                    :class="showUserMenu ? 'bg-gray-50' : ''"
                    @click="showUserMenu = !showUserMenu"
                >
                    <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-xs font-bold text-white">
                        {{ initials }}
                    </span>
                    <span class="flex min-w-0 flex-1 flex-col">
                        <span class="truncate text-[13px] font-semibold text-gray-900">{{ user.name }}</span>
                        <span class="text-[11px] text-gray-500">Admin</span>
                    </span>
                    <i
                        class="pi pi-chevron-up shrink-0 text-[10px] text-gray-400 transition-transform duration-150"
                        :class="showUserMenu ? '' : 'rotate-180'"
                        aria-hidden="true"
                    />
                </button>
            </div>
        </aside>

        <!-- ── Mobile top bar ─────────────────────────────────────────── -->
        <header class="fixed inset-x-0 top-0 z-40 flex h-14 items-center border-b border-gray-200 bg-white pl-1 pr-4 md:hidden">
            <button
                type="button"
                class="flex items-center p-3 text-gray-700"
                aria-label="Otvoriť menu"
                @click="sidebarOpen = true"
            >
                <i class="pi pi-bars text-[20px]" aria-hidden="true" />
            </button>
            <div class="flex flex-1 justify-center">
                <Link :href="route('dashboard')" class="flex items-center gap-2">
                    <svg class="h-6 w-6 shrink-0 fill-current text-emerald-500" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm0 2v5h5l-5-5zm-4 4h8v1.5h-8V8zm0 3h8v1.5h-8V11zm0 3h5v1.5h-5V14z" />
                    </svg>
                    <span class="text-base font-semibold tracking-tight text-gray-900">Invoicius</span>
                </Link>
            </div>
            <Link :href="route('profile.edit')" class="inline-flex h-[34px] w-[34px] shrink-0 items-center justify-center rounded-full bg-emerald-500 text-xs font-bold text-white">
                {{ initials }}
            </Link>
        </header>

        <!-- ── Main ───────────────────────────────────────────────────── -->
        <div class="flex flex-1 flex-col md:ml-[248px]">
            <!-- Flash -->
            <Transition
                enter-active-class="transition ease-out duration-300"
                enter-from-class="opacity-0 -translate-y-2"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition ease-in duration-200"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 -translate-y-2"
            >
                <div
                    v-if="showFlash && (flash.success || flash.error)"
                    class=""
                >
                    <div
                        class="flex items-center justify-between rounded-lg px-4 py-3 text-sm font-medium shadow-sm"
                        :class="flash.success ? 'bg-green-50 text-green-800 ring-1 ring-green-200' : 'bg-red-50 text-red-800 ring-1 ring-red-200'"
                    >
                        <span>{{ flash.success || flash.error }}</span>
                        <button class="ml-4 text-current opacity-50 hover:opacity-100" @click="showFlash = false">&times;</button>
                    </div>
                </div>
            </Transition>

            <main class="flex-1 px-4 pb-6 pt-[72px] md:p-8">
                <slot />
            </main>
        </div>
    </div>
</template>
