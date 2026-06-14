# Invoicius

Fakturačná webová aplikácia – správa faktúr a klientov, export PDF, automatizácie cez n8n.

**Web:** [invoicius.online](https://invoicius.online)

## Stack

| Vrstva | Technológie |
|--------|-------------|
| Backend | PHP 8.2+, Laravel 12 |
| Frontend | Vue 3, TypeScript, Inertia.js, Tailwind CSS, PrimeVue |
| Databáza | MySQL 8 |
| Automatizácie | n8n (cron → webhook → Laravel API) |
| Infra | Docker, GitHub Actions → GHCR → Ubuntu server |

## Funkcionalita

- **Dashboard** – KPI štatistiky, graf stavov faktúr, posledné faktúry, aktívne automatizácie
- **Faktúry** – zoznam, vytvorenie/úprava (položky, DPH, odberateľ), zmena stavu, export PDF, QR platba
- **Klienti** – CRUD, prepojenie s faktúrami
- **Automatizácie** – mesačný report, automatické generovanie faktúr, upozornenia pred splatnosťou
- **Profil** – fakturačné údaje, logo a farba faktúry

## Architektúra

```
app/
├── Automatizations/Handlers/   # handler per typ automatizácie
├── DTOs/                       # typované dáta medzi vrstvami
├── Http/Controllers/           # tenké — len orchestrácia (Web + Api)
├── Policies/                   # ownership enforcement
└── Services/                   # business logika (InvoiceService, AutomatizationProcessor, …)

resources/js/
├── Components/                 # zdieľané komponenty
├── Layouts/
└── Pages/                      # Dashboard / Invoices / Recipients / Automatizations / Profile
    └── <Feature>/
        ├── Components/
        ├── Composables/        # useXxxForm (Inertia useForm wrappery)
        └── Utils/              # defaults, helpers
```

**Automatizácie:**
```
n8n (cron)  →  POST /api/automatizations/process  →  AutomatizationProcessor  →  Handlers
                        ↑ auth: N8N_USER header + N8N_TOKEN
```

## Rýchly štart

```bash
git clone https://github.com/3s7an/invoicius.git && cd invoicius
cp .env.example .env
npm run docker:dev
docker compose -f docker-compose.yml -f docker-compose.dev.yml exec app php artisan db:seed
```

Aplikácia beží na `http://localhost:8080`, test účet: `test@example.com` / `password`.

## Príkazy

### Vývoj

```bash
npm run docker:dev          # spustí celý dev stack (app + mysql + n8n + vite HMR)
npm run types               # regeneruje resources/js/types/index.ts (po každom merge)
```

### Testovanie

```bash
npm run test:all            # FE (typecheck + lint) + BE (PHPUnit) — lokálne
npm run test:fe             # len FE: vue-tsc + ESLint
npm run docker:test:all     # FE + BE testy cez Docker
npm run docker:analyse      # PHPStan / Larastan (nepotrebuje bežiaci app kontajner)
```

### Build & analýza

```bash
npm run build               # produkčný Vite build
npm run docker:rector:dry   # Rector refaktory — náhľad
npm run docker:rector       # Rector refaktory — aplikácia
```

## Deploy

Push na `main` → GitHub Actions: BE testy + FE testy (paralelne) → Docker image → GHCR → SSH deploy → `php artisan migrate --force`.

Secrets: `SERVER_IP`, `SSH_PRIVATE_KEY`, `GITHUB_TOKEN`.
