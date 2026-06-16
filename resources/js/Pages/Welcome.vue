<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import screenshot1 from '@/assets/app-screenshot.png';
import screenshot2 from '@/assets/app-screenshot-2.png';
import screenshot3 from '@/assets/app-screenshot-3.png';
import screenshot4 from '@/assets/app-screenshot-4.png';

const screenshots = [screenshot1, screenshot2, screenshot3, screenshot4];
const current = ref(0);

let timer: ReturnType<typeof setInterval>;

function startTimer() {
    timer = setInterval(() => {
        current.value = (current.value + 1) % screenshots.length;
    }, 3500);
}

onMounted(startTimer);
onUnmounted(() => clearInterval(timer));

function goTo(index: number) {
    current.value = index;
    clearInterval(timer);
    startTimer();
}
</script>

<template>
    <Head title="Invoicius – jednoduchá fakturácia" />

    <div class="page">
        <header class="nav">
            <ApplicationLogo class="logo-color" />
            <Link :href="route('login')" class="nav-cta">
                <i class="pi pi-sign-in" />
                Prihlásiť sa
            </Link>
        </header>

        <!-- ── Hero ────────────────────────────────────── -->
        <section class="hero">
            <div class="hero-glow" aria-hidden="true" />

            <div class="badge">
                <i class="pi pi-bolt" style="font-size: 12px" />
                Fakturácia bez komplikácií
            </div>

            <h1 class="hero-title">
                Faktúry, klienti<br>
                a automatizácie<br>
                <em>na jednom mieste</em>
            </h1>

            <p class="hero-sub">
                Invoicius vám umožní vytvárať faktúry, spravovať klientov a automatizovať
                opakujúce sa úlohy – rýchlo, prehľadne a bez zbytočného klikania.
            </p>

            <!-- <Link :href="route('login')" class="btn-primary" style="margin-top: 40px">
                Prihlásiť sa do aplikácie
                <i class="pi pi-arrow-right" />
            </Link> -->

            <!-- Browser-frame carousel -->
            <div class="browser-frame">
                <div class="browser-chrome">
                    <div class="chrome-dots">
                        <span class="chrome-dot dot-red" />
                        <span class="chrome-dot dot-yellow" />
                        <span class="chrome-dot dot-green" />
                    </div>
                    <div class="chrome-url">invoicius.app</div>
                    <div class="chrome-spacer" />
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
        </section>

        <!-- ── Features ────────────────────────────────── -->
        <section class="features">
            <div class="features-inner">
                <div class="features-heading">
                    <span class="section-tag">Funkcie</span>
                    <h2>Všetko čo potrebujete</h2>
                    <p>Žiadne zbytočné funkcie. Len to podstatné.</p>
                </div>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon icon-sky">
                            <i class="pi pi-file" />
                        </div>
                        <h3>Faktúry</h3>
                        <p>
                            Vytvárajte faktúry v pár klikoch. Sledujte stav každej faktúry –
                            od konceptu až po uhradenie. Export do PDF kedykoľvek.
                        </p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon icon-violet">
                            <i class="pi pi-users" />
                        </div>
                        <h3>Klienti</h3>
                        <p>
                            Spravujte všetkých svojich klientov na jednom mieste. Fakturačné
                            údaje, história a celkový obrat – vždy po ruke.
                        </p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon icon-amber">
                            <i class="pi pi-bolt" />
                        </div>
                        <h3>Automatizácie</h3>
                        <p>
                            Nastavte automatické generovanie faktúr, mesačné reporty alebo
                            pripomienky splatnosti. Systém pracuje za vás.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ── CTA ─────────────────────────────────────── -->
        <section class="cta-section">
            <div class="cta-inner">
                <h2>Pripravení začať?</h2>
                <p>Prihláste sa a spravujte svoju fakturáciu prehľadne a bez starostí.</p>
                <Link :href="route('login')" class="btn-primary" style="margin-top: 32px">
                    Prihlásiť sa
                    <i class="pi pi-arrow-right" />
                </Link>
            </div>
        </section>

        <!-- ── Footer ──────────────────────────────────── -->
        <footer class="footer">
            <ApplicationLogo class="logo-color" />
            <span>© 2026 Invoicius. Všetky práva vyhradené.</span>
        </footer>
</div>
</template>

<style scoped>
*, *::before, *::after { box-sizing: border-box; }

.page {
    font-family: var(--font-sans);
    background: var(--surface-app);
    color: var(--text-body);
    -webkit-font-smoothing: antialiased;
    min-height: 100vh;
}

/* ── Nav ─────────────────────────────────────── */
.nav {
    position: fixed; top: 0; left: 0; right: 0; z-index: 20;
    height: 64px;
    background: var(--primary);
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 40px;
}
.nav .logo-color { color: #fff; }
.nav-cta {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 20px; background: #fff; color: var(--primary-active);
    font-size: 14px; font-weight: 600; border: none;
    border-radius: var(--radius-sm); cursor: pointer;
    text-decoration: none; box-shadow: var(--shadow-sm);
    transition: background var(--duration-fast) var(--ease);
}
.nav-cta:hover { background: var(--emerald-50); }

/* ── Hero ────────────────────────────────────── */
.hero {
    position: relative; overflow: hidden;
    background: #fff;
    display: flex; flex-direction: column; align-items: center;
    padding: 148px 24px 80px; text-align: center;
}
.hero-glow {
    position: absolute; top: -80px; left: 50%; transform: translateX(-50%);
    width: 1000px; height: 640px; pointer-events: none;
    background: radial-gradient(ellipse at 50% 30%, var(--emerald-100) 0%, transparent 65%);
    opacity: 0.65;
}
.badge {
    position: relative;
    display: inline-flex; align-items: center; gap: 6px;
    padding: 5px 14px; background: var(--primary-soft); color: var(--primary-active);
    font-size: 13px; font-weight: 600; border-radius: var(--radius-full);
    border: 1px solid var(--emerald-200); margin-bottom: 28px; letter-spacing: 0.01em;
}
.hero-title {
    position: relative;
    font-size: clamp(2.5rem, 6vw, 4.25rem); font-weight: 800;
    letter-spacing: -0.035em; line-height: 1.08;
    color: var(--text-strong); max-width: 740px; text-wrap: balance;
}
.hero-title em {
    font-style: normal;
    background: linear-gradient(135deg, var(--emerald-500), var(--emerald-700));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
}
.hero-sub {
    position: relative;
    margin-top: 24px; font-size: clamp(1rem, 2vw, 1.15rem);
    color: var(--text-muted); max-width: 500px; line-height: 1.65;
}
.btn-primary {
    position: relative;
    display: inline-flex; align-items: center; gap: 8px;
    padding: 14px 28px; background: var(--primary); color: #fff;
    font-family: var(--font-sans); font-size: 15px; font-weight: 600;
    border: none; border-radius: var(--radius-sm); cursor: pointer;
    text-decoration: none;
    box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
    transition:
        background var(--duration-fast) var(--ease),
        box-shadow var(--duration-fast) var(--ease),
        transform var(--duration-fast) var(--ease);
}
.btn-primary:hover {
    background: var(--primary-hover);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.45);
    transform: translateY(-1px);
}
.btn-primary:active {
    background: var(--primary-active);
    box-shadow: var(--shadow-sm);
    transform: none;
}

/* ── Browser frame ───────────────────────────── */
.browser-frame {
    position: relative;
    margin-top: 64px; width: 100%; max-width: 960px;
    border-radius: var(--radius-xl);
    box-shadow: 0 32px 80px rgba(0, 0, 0, 0.14), 0 0 0 1px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}
.browser-chrome {
    height: 44px; background: var(--gray-100);
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; padding: 0 16px; gap: 12px;
}
.chrome-dots { display: flex; gap: 6px; flex-shrink: 0; }
.chrome-dot { width: 12px; height: 12px; border-radius: 50%; }
.dot-red    { background: #ff5f57; }
.dot-yellow { background: #febc2e; }
.dot-green  { background: #28c840; }
.chrome-url {
    flex: 1; text-align: center; background: #fff;
    border: 1px solid var(--border); border-radius: var(--radius-sm);
    padding: 4px 12px; font-size: 12px; color: var(--text-muted);
    max-width: 220px; margin: 0 auto;
}
.chrome-spacer { width: 52px; flex-shrink: 0; }

.carousel-track {
    display: grid;
    background: #fff;
}
.carousel-slide {
    grid-row: 1; grid-column: 1;
    display: block; width: 100%; height: auto;
    opacity: 0; transition: opacity 0.55s var(--ease);
}
.carousel-slide.active { opacity: 1; }

.carousel-dots {
    display: flex; justify-content: center; align-items: center; gap: 8px;
    padding: 14px 0; background: var(--gray-50);
    border-top: 1px solid var(--border-subtle);
}
.dot {
    height: 8px; width: 8px; border-radius: var(--radius-full);
    background: var(--gray-300); border: none; cursor: pointer; padding: 0;
    transition:
        background var(--duration-fast) var(--ease),
        width var(--duration) var(--ease);
}
.dot.active { background: var(--primary); width: 22px; }
.dot:hover:not(.active) { background: var(--gray-400); }

/* ── Features ────────────────────────────────── */
.features { padding: 96px 24px; }
.features-inner { max-width: 1000px; margin: 0 auto; }
.features-heading { text-align: center; margin-bottom: 56px; }
.section-tag {
    display: inline-block;
    padding: 3px 12px; background: var(--primary-soft); color: var(--primary-active);
    font-size: 11px; font-weight: 700; letter-spacing: 0.07em; text-transform: uppercase;
    border-radius: var(--radius-full); border: 1px solid var(--emerald-200); margin-bottom: 14px;
}
.features-heading h2 {
    font-size: clamp(1.75rem, 4vw, 2.5rem); font-weight: 800;
    letter-spacing: -0.025em; color: var(--text-strong); margin-bottom: 10px;
}
.features-heading p { font-size: 1.05rem; color: var(--text-muted); }
.features-grid {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;
}
.feature-card {
    background: var(--surface-card); border: 1px solid var(--border);
    border-radius: var(--radius-xl); padding: 32px; box-shadow: var(--shadow-sm);
    transition:
        box-shadow var(--duration) var(--ease),
        transform var(--duration) var(--ease),
        border-color var(--duration) var(--ease);
}
.feature-card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-3px);
    border-color: var(--border-strong);
}
.feature-icon {
    width: 52px; height: 52px; border-radius: var(--radius-lg);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; margin-bottom: 20px;
}
.icon-sky    { background: var(--accent-sky-soft);    color: var(--accent-sky); }
.icon-violet { background: var(--accent-violet-soft); color: var(--accent-violet); }
.icon-amber  { background: var(--accent-amber-soft);  color: var(--accent-amber); }
.feature-card h3 { font-size: 17px; font-weight: 700; color: var(--text-strong); margin-bottom: 10px; }
.feature-card p  { font-size: 14px; color: var(--text-muted); line-height: 1.7; }

/* ── CTA ─────────────────────────────────────── */
.cta-section {
    padding: 96px 24px;
    background: #fff;
    border-top: 1px solid var(--border-subtle);
}
.cta-inner {
    max-width: 560px; margin: 0 auto;
    display: flex; flex-direction: column; align-items: center; text-align: center;
}
.cta-inner h2 {
    font-size: clamp(1.75rem, 4vw, 2.75rem); font-weight: 800;
    letter-spacing: -0.025em; color: var(--text-strong); text-wrap: balance;
    margin-bottom: 14px;
}
.cta-inner p { font-size: 1.05rem; color: var(--text-muted); line-height: 1.65; }

/* ── Footer ──────────────────────────────────── */
.footer {
    padding: 24px 40px;
    background: var(--gray-900);
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 12px;
    font-size: 13px; color: var(--gray-500);
}
.footer .logo-color { color: rgba(255, 255, 255, 0.85); }

/* ── Mobile ──────────────────────────────────── */
@media (max-width: 640px) {
    .nav { padding: 0 20px; }
    .footer { flex-direction: column; text-align: center; }
}
</style>
