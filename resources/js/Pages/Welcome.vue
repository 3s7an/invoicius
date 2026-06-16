<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import screenshot1 from '@/assets/app-screenshot.png';
import screenshot2 from '@/assets/app-screenshot-2.png';
import screenshot3 from '@/assets/app-screenshot-3.png';
import screenshot4 from '@/assets/app-screenshot-4.png';

const screenshots = [screenshot1, screenshot2, screenshot3, screenshot4];
const urls = ['dashboard', 'invoices', 'recipients', 'automatizations'];
const current = ref(0);
const scrolled = ref(false);

let timer: ReturnType<typeof setInterval>;

function startTimer() {
    timer = setInterval(() => {
        current.value = (current.value + 1) % screenshots.length;
    }, 3500);
}

function onScroll() {
    scrolled.value = window.scrollY > 10;
}

onMounted(() => {
    window.addEventListener('scroll', onScroll, { passive: true });
    startTimer();
});

onUnmounted(() => {
    window.removeEventListener('scroll', onScroll);
    clearInterval(timer);
});

function goTo(index: number) {
    current.value = index;
    clearInterval(timer);
    startTimer();
}
</script>

<template>
    <Head title="Invoicius – jednoduchá fakturácia" />

    <div class="page">
        <header class="nav" :class="{ scrolled }">
            <ApplicationLogo />
            <Link :href="route('login')" class="btn-nav">
                Prihlásiť sa
            </Link>
        </header>

        <!-- Hero -->
        <section class="hero">
            <div class="container">
                <h1>
                    Faktúry, klienti<br>
                    a automatizácie<br>
                    <span>na jednom mieste</span>
                </h1>
                <p class="hero-sub">
                    Vytvárajte faktúry, spravujte klientov a automatizujte opakujúce sa úlohy –
                    bez zbytočného klikania.
                </p>
                <div class="hero-cta">
                    <Link :href="route('login')" class="btn-primary">
                        Prihlásiť sa do aplikácie <i class="pi pi-arrow-right" />
                    </Link>
                </div>
                <p class="hero-tagline">
                    <span class="tagline-dot" />
                    Slovenská fakturácia · DPH · PDF export · Automatizácie
                </p>

                <!-- Browser-frame carousel -->
                <div class="mockup-outer">
                    <div class="mockup-browser">
                        <div class="browser-bar">
                            <div class="browser-dot" style="background: #ff5f57" />
                            <div class="browser-dot" style="background: #febc2e" />
                            <div class="browser-dot" style="background: #28c840" />
                            <div class="browser-url">
                                <i class="pi pi-lock" />
                                invoicius.online/{{ urls[current] }}
                            </div>
                        </div>
                        <div class="carousel-track">
                            <img
                                v-for="(src, i) in screenshots"
                                :key="i"
                                :src="src"
                                alt="Ukážka aplikácie Invoicius"
                                class="carousel-slide"
                                :class="{ active: i === current }"
                            >
                        </div>
                        <div class="carousel-dots">
                            <button
                                v-for="(_, i) in screenshots"
                                :key="i"
                                class="dot"
                                :class="{ active: i === current }"
                                :aria-label="`Snímka ${i + 1}`"
                                @click="goTo(i)"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Trust bar -->
        <div class="trust">
            <div class="container">
                <div class="trust-inner">
                    <div class="trust-item">
                        <div class="trust-num">2<span>min</span></div>
                        <div class="trust-label">Faktúra hotová za 2 minúty</div>
                    </div>
                    <div class="trust-item">
                        <div class="trust-num">100<span>%</span></div>
                        <div class="trust-label">Slovenská lokalizácia a DPH</div>
                    </div>
                    <div class="trust-item">
                        <div class="trust-num">PDF<span>.</span></div>
                        <div class="trust-label">Export faktúr kedykoľvek</div>
                    </div>
                    <div class="trust-item">
                        <div class="trust-num">24<span>/7</span></div>
                        <div class="trust-label">Automatizácie bežia non-stop</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features -->
        <section class="features">
            <div class="container">
                <h2 class="section-title">Všetko čo potrebujete k fakturácii</h2>
                <p class="section-sub">Žiadne zbytočné funkcie. Len to, čo každý deň skutočne využijete.</p>
                <div class="features-grid">
                    <div class="feat">
                        <div class="feat-header">
                            <div class="feat-icon icon-sky"><i class="pi pi-file" /></div>
                            <span class="feat-num">01</span>
                        </div>
                        <h3>Faktúry</h3>
                        <p>Vystavte faktúru za minútu. Sledujte každý stav – od konceptu až po uhradenie.</p>
                        <ul class="feat-list">
                            <li>Export do PDF kedykoľvek</li>
                            <li>Položky s DPH a zľavami</li>
                            <li>Stav platby v reálnom čase</li>
                        </ul>
                    </div>
                    <div class="feat">
                        <div class="feat-header">
                            <div class="feat-icon icon-violet"><i class="pi pi-users" /></div>
                            <span class="feat-num">02</span>
                        </div>
                        <h3>Klienti</h3>
                        <p>Správa klientov vrátane IČO, DIČ, adresy a kompletnej histórie fakturácie.</p>
                        <ul class="feat-list">
                            <li>Kompletné fakturačné údaje</li>
                            <li>História faktúr podľa klienta</li>
                            <li>Celkový obrat na jednom mieste</li>
                        </ul>
                    </div>
                    <div class="feat">
                        <div class="feat-header">
                            <div class="feat-icon icon-amber"><i class="pi pi-bolt" /></div>
                            <span class="feat-num">03</span>
                        </div>
                        <h3>Automatizácie</h3>
                        <p>Nastavte raz, systém funguje za vás – mesačné faktúry, reporty, pripomienky.</p>
                        <ul class="feat-list">
                            <li>Automatické generovanie faktúr</li>
                            <li>Mesačné reporty e-mailom</li>
                            <li>Pripomienky pred splatnosťou</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="cta-section">
            <div class="cta-inner">
                <div class="cta-left">
                    <h2>Začnite<br>fakturovať<br><span>dnes</span></h2>
                    <p>Prihláste sa a spravujte svoju fakturáciu prehľadne, rýchlo a bez zbytočného klikania.</p>
                    <Link :href="route('login')" class="btn-primary" style="margin-top: 32px">
                        Prihlásiť sa <i class="pi pi-arrow-right" />
                    </Link>
                </div>
                <div class="cta-right">
                    <div class="cta-tile">
                        <div class="cta-tile-num">2<span>min</span></div>
                        <div class="cta-tile-label">Faktúra za 2 minúty</div>
                    </div>
                    <div class="cta-tile">
                        <div class="cta-tile-num">100<span>%</span></div>
                        <div class="cta-tile-label">Slovenská lokalizácia</div>
                    </div>
                    <div class="cta-tile">
                        <div class="cta-tile-num">PDF<span>.</span></div>
                        <div class="cta-tile-label">Export kedykoľvek</div>
                    </div>
                    <div class="cta-tile">
                        <div class="cta-tile-num">24<span>/7</span></div>
                        <div class="cta-tile-label">Automatizácie non-stop</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            <span>Invoicius · 2026</span>
        </footer>
</div>
</template>

<style scoped>
*, *::before, *::after { box-sizing: border-box; }

.page {
    font-family: var(--font-sans);
    background: #fff;
    color: var(--gray-700);
    -webkit-font-smoothing: antialiased;
    line-height: 1.5;
}

/* ── Container ───────────────────────────────── */
.container {
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 clamp(20px, 5vw, 60px);
}

/* ── Nav ─────────────────────────────────────── */
.nav {
    position: fixed; top: 0; left: 0; right: 0; z-index: 50;
    height: 64px; background: transparent;
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 clamp(20px, 5vw, 60px);
    transition: background var(--duration) var(--ease), box-shadow var(--duration) var(--ease);
}
.nav.scrolled {
    background: rgba(255, 255, 255, 0.92);
    backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
    box-shadow: 0 1px 0 rgba(0, 0, 0, 0.06);
}
.nav :deep(svg) { color: var(--primary); }
.btn-nav {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 22px; background: var(--primary); color: #fff;
    font-family: var(--font-sans); font-size: 14px; font-weight: 600;
    border: none; border-radius: var(--radius-sm); cursor: pointer; text-decoration: none;
    box-shadow: 0 1px 3px rgba(16, 185, 129, 0.4), 0 4px 12px rgba(16, 185, 129, 0.2);
    transition: background var(--duration-fast) var(--ease),
                box-shadow var(--duration-fast) var(--ease),
                transform 0.1s var(--ease);
}
.btn-nav:hover {
    background: var(--primary-hover);
    box-shadow: 0 1px 3px rgba(16, 185, 129, 0.5), 0 6px 18px rgba(16, 185, 129, 0.3);
    transform: translateY(-1px);
}
.btn-nav:active { transform: none; }

/* ── Hero ────────────────────────────────────── */
.hero {
    padding: 148px 0 80px;
    background: linear-gradient(180deg, #f8fffe 0%, #fff 60%);
    text-align: center;
    border-bottom: 1px solid var(--gray-100);
}
.hero h1 {
    font-size: clamp(2.4rem, 5.5vw, 3.75rem);
    font-weight: 700; letter-spacing: -0.03em; line-height: 1.1;
    color: var(--gray-900); text-wrap: balance;
    max-width: 760px; margin: 0 auto;
}
.hero h1 span { color: var(--primary); }
.hero-sub {
    margin: 20px auto 0; max-width: 480px;
    font-size: clamp(1rem, 1.8vw, 1.125rem);
    color: var(--gray-500); line-height: 1.65;
}
.hero-cta {
    margin-top: 36px;
    display: flex; align-items: center; justify-content: center; gap: 12px; flex-wrap: wrap;
}
.hero-tagline {
    margin-top: 18px; font-size: 12.5px; color: var(--gray-400);
    display: flex; align-items: center; justify-content: center; gap: 6px;
}
.tagline-dot {
    display: inline-block; width: 6px; height: 6px; border-radius: 50%;
    background: var(--primary); flex-shrink: 0;
}

/* ── Shared button ───────────────────────────── */
.btn-primary {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 13px 28px; background: var(--primary); color: #fff;
    font-family: var(--font-sans); font-size: 15px; font-weight: 600;
    border: none; border-radius: var(--radius-sm); cursor: pointer; text-decoration: none;
    box-shadow: 0 1px 3px rgba(16, 185, 129, 0.4), 0 4px 12px rgba(16, 185, 129, 0.25);
    transition: background var(--duration-fast) var(--ease),
                box-shadow var(--duration-fast) var(--ease),
                transform 0.1s var(--ease);
}
.btn-primary:hover {
    background: var(--primary-hover);
    box-shadow: 0 1px 3px rgba(16, 185, 129, 0.5), 0 6px 20px rgba(16, 185, 129, 0.3);
    transform: translateY(-1px);
}
.btn-primary:active { transform: none; }

/* ── Browser frame ───────────────────────────── */
.mockup-outer { margin: 56px auto 0; max-width: 960px; }
.mockup-browser {
    background: var(--gray-100); border: 1px solid var(--gray-200);
    border-radius: var(--radius-xl); overflow: hidden;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04), 0 8px 24px rgba(0, 0, 0, 0.06), 0 32px 64px rgba(0, 0, 0, 0.08);
}
.browser-bar {
    height: 40px; background: var(--gray-100); border-bottom: 1px solid var(--gray-200);
    display: flex; align-items: center; gap: 8px; padding: 0 16px;
}
.browser-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
.browser-url {
    flex: 1; margin: 0 12px; max-width: 240px;
    height: 24px; background: #fff; border: 1px solid var(--gray-200);
    border-radius: var(--radius-sm);
    display: flex; align-items: center; gap: 5px; padding: 0 10px;
    font-size: 11px; color: var(--gray-400);
}
.browser-url i { font-size: 9px; }

/* ── Carousel ────────────────────────────────── */
.carousel-track { display: grid; background: #fff; }
.carousel-slide {
    grid-row: 1; grid-column: 1;
    display: block; width: 100%; height: auto;
    opacity: 0; transition: opacity 0.55s var(--ease);
}
.carousel-slide.active { opacity: 1; }
.carousel-dots {
    display: flex; justify-content: center; align-items: center; gap: 8px;
    padding: 14px 0; background: var(--gray-50); border-top: 1px solid var(--gray-200);
}
.dot {
    height: 8px; width: 8px; border-radius: var(--radius-full);
    background: var(--gray-300); border: none; cursor: pointer; padding: 0;
    transition: background var(--duration-fast) var(--ease), width var(--duration) var(--ease);
}
.dot.active { background: var(--primary); width: 22px; }
.dot:hover:not(.active) { background: var(--gray-400); }

/* ── Trust bar ───────────────────────────────── */
.trust { padding: 32px 0; background: var(--gray-900); }
.trust-inner { display: grid; grid-template-columns: repeat(4, 1fr); gap: 2px; }
.trust-item {
    display: flex; flex-direction: column; align-items: center;
    padding: 20px 24px; background: #1a2333; text-align: center;
}
.trust-item:first-child { border-radius: var(--radius-lg) 0 0 var(--radius-lg); }
.trust-item:last-child  { border-radius: 0 var(--radius-lg) var(--radius-lg) 0; }
.trust-num {
    font-size: 1.75rem; font-weight: 700; letter-spacing: -0.03em;
    color: #fff; line-height: 1; margin-bottom: 6px;
}
.trust-num span { color: var(--primary); }
.trust-label { font-size: 13px; color: rgba(255, 255, 255, 0.5); font-weight: 500; }

/* ── Features ────────────────────────────────── */
.features { padding: 96px 0; background: #fff; }
.section-title {
    font-size: clamp(1.75rem, 3.5vw, 2.5rem); font-weight: 700;
    letter-spacing: -0.025em; color: var(--gray-900);
    text-wrap: balance; max-width: 560px;
}
.section-sub {
    margin-top: 12px; font-size: 1.05rem; color: var(--gray-500);
    max-width: 480px; line-height: 1.65;
}
.features-grid {
    margin-top: 56px;
    display: grid; grid-template-columns: repeat(3, 1fr);
    grid-template-rows: repeat(4, auto);
    gap: 2px; background: var(--gray-200);
    border: 1px solid var(--gray-200); border-radius: var(--radius-xl); overflow: hidden;
}
.feat {
    padding: 36px 32px; background: #fff;
    display: grid; grid-template-rows: subgrid; grid-row: span 4;
    transition: background var(--duration-fast) var(--ease);
}
.feat:hover { background: var(--gray-50); }
.feat-header { display: flex; justify-content: space-between; align-items: flex-end; }
.feat-num {
    font-size: 11px; font-weight: 700; color: rgba(17, 24, 39, 0.18);
    letter-spacing: 0.04em; line-height: 1; font-variant-numeric: tabular-nums;
}
.feat-icon {
    width: 44px; height: 44px; border-radius: var(--radius-lg);
    display: flex; align-items: center; justify-content: center; font-size: 18px;
}
.icon-sky    { background: var(--accent-sky-soft);    color: var(--accent-sky); }
.icon-violet { background: var(--accent-violet-soft); color: var(--accent-violet); }
.icon-amber  { background: var(--accent-amber-soft);  color: var(--accent-amber); }
.feat h3 {
    font-size: 16px; font-weight: 700; color: var(--gray-900);
    padding-top: 16px; margin: 0;
}
.feat p { font-size: 14px; color: var(--gray-500); line-height: 1.7; margin: 0; }
.feat-list {
    list-style: none; padding: 0; margin: 0;
    display: flex; flex-direction: column; gap: 6px;
}
.feat-list li {
    display: flex; align-items: flex-start; gap: 8px;
    font-size: 13px; color: var(--gray-500);
}
.feat-list li::before {
    content: ''; width: 5px; height: 5px; border-radius: 50%;
    background: var(--primary); flex-shrink: 0; margin-top: 7px;
}

/* ── CTA ─────────────────────────────────────── */
.cta-section {
    background: var(--gray-900);
    padding: 100px clamp(20px, 5vw, 60px);
}
.cta-inner {
    max-width: 1100px; margin: 0 auto;
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 60px; align-items: center;
}
.cta-left h2 {
    font-size: clamp(2rem, 4vw, 3rem); font-weight: 700;
    letter-spacing: -0.03em; line-height: 1.1; color: #fff; text-wrap: balance;
}
.cta-left h2 span { color: var(--primary); }
.cta-left p {
    margin-top: 16px; font-size: 1.05rem;
    color: rgba(255, 255, 255, 0.5); line-height: 1.65; max-width: 380px;
}
.cta-right {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 2px; border-radius: var(--radius-lg); overflow: hidden;
}
.cta-tile {
    background: #1a2333; padding: 28px 24px;
    display: flex; flex-direction: column; gap: 6px;
}
.cta-tile:nth-child(1) { border-radius: var(--radius-lg) 0 0 0; }
.cta-tile:nth-child(2) { border-radius: 0 var(--radius-lg) 0 0; }
.cta-tile:nth-child(3) { border-radius: 0 0 0 var(--radius-lg); }
.cta-tile:nth-child(4) { border-radius: 0 0 var(--radius-lg) 0; }
.cta-tile-num {
    font-size: 1.875rem; font-weight: 700; letter-spacing: -0.03em;
    color: #fff; line-height: 1;
}
.cta-tile-num span { color: var(--primary); }
.cta-tile-label { font-size: 12.5px; color: rgba(255, 255, 255, 0.4); font-weight: 500; }

/* ── Footer ──────────────────────────────────── */
.footer {
    background: #0d1117; padding: 20px clamp(20px, 5vw, 60px);
    text-align: center; border-top: 1px solid rgba(255, 255, 255, 0.05);
    font-size: 12.5px; color: rgba(255, 255, 255, 0.2);
    font-family: var(--font-sans);
}

/* ── Responsive ──────────────────────────────── */
@media (max-width: 768px) {
    .cta-inner { grid-template-columns: 1fr; }
    .cta-right { display: none; }
    .features-grid { grid-template-columns: 1fr; grid-template-rows: auto; background: #fff; }
    .feat { display: block; grid-row: auto; border-bottom: 1px solid var(--gray-200); }
    .feat:last-child { border-bottom: none; }
    .feat h3 { margin-top: 16px; padding-top: 0; }
    .feat p { margin-top: 8px; }
    .feat-list { margin-top: 16px; }
}
@media (max-width: 640px) {
    .trust-inner { grid-template-columns: repeat(2, 1fr); }
    .trust-item:first-child { border-radius: var(--radius-lg) 0 0 0; }
    .trust-item:nth-child(2) { border-radius: 0 var(--radius-lg) 0 0; }
    .trust-item:nth-child(3) { border-radius: 0 0 0 var(--radius-lg); }
    .trust-item:last-child   { border-radius: 0 0 var(--radius-lg) 0; }
}
</style>
