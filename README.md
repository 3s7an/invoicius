# Invoicius

Smart fakturačná webová aplikácia – správa faktúr a odberateľov, prehľad stavov, export do PDF a automatizácie spracované cez n8n.

**Web:** [invoicius.online](https://invoicius.online)  
**GitHub:** [github.com/3s7an/invoicius](https://github.com/3s7an/invoicius)

## Technológie

| Vrstva | Stack |
|--------|--------|
| Backend | PHP 8.2+, Laravel 12 |
| Frontend | Vue 3, Inertia.js, Tailwind CSS, PrimeVue, Chart.js |
| PDF | Spatie Laravel PDF (DomPDF), QR platby |
| Databáza | MySQL 8 (Docker), SQLite (voliteľne bez Dockeru) |
| Automatizácie | n8n (cron webhook → Laravel API) |
| Infra | Docker, Docker Compose, GitHub Actions → GHCR → Ubuntu server |

## Funkcie

- **Dashboard** – KPI (faktúry, klienti, automatizácie), graf rozdelenia podľa stavu, posledné faktúry, aktívne automatizácie
- **Faktúry** – zoznam, vytvorenie/úprava (položky, DPH, odberateľ), zmena stavu, PDF, zmazanie
- **Klienti (odberatelia)** – CRUD, prepojenie s faktúrami
- **Automatizácie** – mesačný report, automatické generovanie faktúr, upozornenia X dní pred splatnosťou
- **Profil** – účet, fakturačné údaje, logo a farba faktúry, heslo

### Automatizácie a n8n

Aplikácia ukladá pravidlá automatizácií v DB. **n8n** podľa cronu volá API endpoint, Laravel spracuje dnešné úlohy cez handler pattern a vráti výsledok (napr. odoslanie e-mailu rieši workflow v n8n).

```
n8n (cron)  →  POST /api/automatizations/process  →  AutomatizationProcessor  →  Handlers
                                                              ↑
                                                    auth: N8N_USER header + N8N_TOKEN
```

Typy handlerov (`app/Automatizations/Handlers/`):

- `invoice_report` – mesačný report
- `invoice_auto_gen` – automatické vystavenie faktúry
- `invoice_due_reminder` – pripomienka pred splatnosťou

Exportované workflowy: `n8n-workflows/` (import do vlastnej inštancie n8n).

## Rýchly štart (Docker – odporúčané)

### Požiadavky

- Docker a Docker Compose
- Git

### 1. Konfigurácia

```bash
git clone https://github.com/3s7an/invoicius.git invoicer-app
cd invoicer-app
cp .env.example .env
```

### 2. Spustenie

```bash
docker compose up -d --build
```

| Služba | URL / port |
|--------|------------|
| Aplikácia | http://localhost:8080 (`APP_PORT`) |
| MySQL | localhost:3307 (`MYSQL_PORT`) |
| n8n | http://localhost:5678 (`N8N_PORT`) |

### 3. Seedery (povinné)

**Po každom spustení stacku** (nový clone, `docker compose up`, prázdna alebo resetnutá databáza) spusti seedery. Migrácie sa pri štarte kontajnera `app` spúšťajú automaticky (`docker/entrypoint.sh`); referenčné dáta musíš doplniť ručne:

```bash
docker compose exec app php artisan db:seed
```

Bez seedov aplikácia nebude plne fungovať – chýbajú meny, typy DPH, stavy faktúr, farby faktúr a formuláre (napr. vytvorenie faktúry) môžu padať na validácii.

Ak potrebuješ migrácie aj seed naraz (napr. bez Docker entrypointu):

```bash
docker compose exec app php artisan migrate --seed
```

`DatabaseSeeder` naplní referenčné tabuľky a vytvorí test účet `test@example.com` / heslo `password`. Seedery sú idempotentné (`updateOrCreate`) – opakované spustenie je bezpečné.

| Seeder | Čo doplní |
|--------|-----------|
| `CurrencySeeder` | Meny (€, Kč, $) |
| `VatTypeSeeder` | Typy DPH |
| `InvoiceStatusSeeder` | Stavy faktúr |
| `InvoiceColorSeeder` | Predvolené farby faktúry |
| `InvoiceSeeder` | Ukážkové faktúry (ak ešte žiadne nie sú) |

### Vývoj s HMR (Vite)

```bash
docker compose -f docker-compose.yml -f docker-compose.dev.yml up -d --build
# alebo
npm run docker:dev
```

Po spustení dev stacku tiež spusti seedery (rovnaký príkaz ako v kroku 3):

```bash
docker compose -f docker-compose.yml -f docker-compose.dev.yml exec app php artisan db:seed
```

## Lokálny vývoj bez Dockeru

Ak preferuješ klasický setup:

```bash
composer install
cp .env.example .env
php artisan key:generate
# DB: sqlite alebo mysql v .env
php artisan migrate --seed   # povinné – rovnaké referenčné dáta ako v Dockeri
npm install && npm run build
php artisan serve
```

Pri každom novom prostredí alebo prázdnej databáze znova spusti `php artisan migrate --seed` (prípadne len `php artisan db:seed`).

Súčasne frontend:

```bash
composer run dev
# alebo: php artisan serve + npm run dev
```

Jednorazový setup:

```bash
composer run setup
```

## Premenné prostredia (výber)

| Premenná | Popis |
|----------|--------|
| `N8N_USER` | Názov HTTP hlavičky pre auth z n8n (default `n8n-user`) |
| `N8N_TOKEN` | Zdieľaný token – musí sedieť s n8n workflow |
| `APP_PORT` | Port aplikácie (default `8080`) |
| `N8N_PORT` | Port n8n UI (default `5678`) |
| `RUN_SEED` | Voliteľne pri štarte kontajnera spusti seed (`true`/`false`, default `false`). Odporúčané je ručné `migrate --seed` podľa kroku 3 vyššie. |

## Architektúra (výber)

```
app/
├── Automatizations/Handlers/   # Logika podľa typu automatizácie
├── Contracts/                  # Rozhrania služieb
├── DTOs/                       # Validované dáta medzi vrstvami
├── Http/
│   ├── Controllers/            # Web (Inertia) + Api (n8n)
│   ├── Middleware/
│   ├── Requests/
│   └── Traits/VerifiesN8nRequests.php
├── Models/
├── Policies/
└── Services/                   # InvoiceService, DashboardService, AutomatizationProcessor, …

resources/js/
├── Components/                 # PageHeader, InvoiceStatsPie, …
├── Layouts/
└── Pages/
    ├── Dashboard/
    ├── Automatizations/
    ├── Invoices/
    ├── Recipients/
    └── Profile/

n8n-workflows/                  # JSON exporty workflowov
docker-compose.yml              # app + mysql + n8n
docker-compose.dev.yml          # overlay: vite HMR, bind mounts
```

**Princípy:** tenké controllery, business logika v `Services`, automatizácie cez registráciu handlerov v `AutomatizationProcessor`.

## Deploy (produkcia)

Pri pushi na `main` beží GitHub Actions (`.github/workflows/deploy.yml`):

1. Build Docker image → push na **GHCR** (`ghcr.io/<repo>:latest`)
2. SSH na server → `docker compose -f docker-compose.prod.yml pull/up`
3. `php artisan migrate --force`

Pri **prvom nasadení** na čistú produkčnú databázu spusti aj seedery (jednorazovo):

```bash
docker compose -f docker-compose.prod.yml exec -T app php artisan db:seed --force
```

Produkčný `docker-compose.prod.yml` je na serveri (nie v repozitári). Secrets: `SERVER_IP`, `SSH_PRIVATE_KEY`, `GITHUB_TOKEN`.

## Testovanie

```bash
# v Dockeri
docker compose exec app php artisan test

# lokálne
composer run test
# alebo: vendor/bin/phpunit
```

## Licencia

MIT
