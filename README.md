# Invoicius

Webová aplikácia na správu faktúr – vytváranie faktúr, evidencia odberateľov, prehľad stavov a export do PDF.

## Technológie

- **Backend:** PHP 8.2+, Laravel 12
- **Frontend:** Vue 3, Inertia.js, Tailwind CSS, PrimeVue
- **PDF:** Spatie Laravel PDF (DomPDF)
- **Autentifikácia:** Laravel Breeze

## Požiadavky

- PHP 8.2+
- Composer
- Node.js 18+ a npm
- MySQL / MariaDB alebo SQLite (odporúčané pre lokálny vývoj)

## Inštalácia

1. Naklonuj repozitár a nainštaluj závislosti:

```bash
git clone <repo-url> invoicer-app
cd invoicer-app
composer install
cp .env.example .env
php artisan key:generate
```

2. Nastav databázu v `.env` (napr. SQLite):

```env
DB_CONNECTION=sqlite
# alebo MySQL:
# DB_CONNECTION=mysql
# DB_DATABASE=invoicius
# DB_USERNAME=...
# DB_PASSWORD=...
```

3. Spusti migrácie a seedery:

```bash
php artisan migrate
php artisan db:seed
```

4. Nainštaluj frontend a zostav assets:

```bash
npm install
npm run build
```

5. Spusti aplikáciu:

```bash
php artisan serve
```

Aplikácia beží na `http://127.0.0.1:8000`.

### Jednorazový setup (Composer)

```bash
composer run setup
```

Vykoná: `composer install`, skopíruje `.env.example` → `.env`, vygeneruje kľúč, migrácie, `npm install` a `npm run build`.

## Vývoj

Súčasne spusti Laravel server, Vite a (voliteľne) logy:

```bash
composer run dev
```

Alebo manuálne v dvoch termináloch:

```bash
php artisan serve
npm run dev
```

## Funkcie

- **Dashboard** – prehľad súm podľa stavov (vystavené, zaplatené, čakajúce, po splatnosti) s farebnými indikátormi
- **Faktúry** – zoznam, vytvorenie novej faktúry (položky, DPH, odberateľ), zmena stavu, stiahnutie PDF, zmazanie
- **Odberatelia** – CRUD (meno, firma, adresa, IČO, DIČ, IBAN), prepojenie s faktúrami
- **Profil** – údaje účtu, fakturačné údaje (adresa, IČO, mena), nastavenia faktúr (logo firmy, predvolená farba), zmena hesla, zrušenie účtu

## Štruktúra projektu (výber)

```
app/
├── Contracts/          # Rozhrania služieb
├── Http/
│   ├── Controllers/    # Dashboard, Invoice, Recipient, Profile
│   ├── Middleware/     # HandleInertiaRequests (shared props, flash)
│   └── Requests/       # Validácia (StoreInvoice, UpdateProfileDetails, …)
├── Models/             # User, Invoice, Recipient, InvoiceStatus, VatType, …
└── Services/           # InvoiceService, RecipientService, ProfileService

resources/js/
├── Components/         # InvoiceSettings, InvoiceItemsTable, BillingDetailsForm, …
├── Layouts/            # AuthenticatedLayout, GuestLayout
└── Pages/              # Dashboard, Invoices, Recipients, Profile, Auth
```

## Testovanie

```bash
composer run test
# alebo
php artisan test
```

## Licencia

MIT
