# Code Review – Invoicer App

---

## 1. Architektúra a štruktúra projektu

### 1.2 `InvoiceService::getRecipients()` duplikuje `RecipientService::listForUser()`
- **Súbor:** `app/Services/InvoiceService.php` (riadok 63–69)
- `getRecipients()` v invoice servise robí to isté čo `RecipientService::listForUser()`. Volá sa len z `getCreateFormData()`.
- **Návrh:** Odstrániť a použiť `RecipientService` cez DI.

---

## 2. Bezpečnosť
### 2.2 Dvojitá autorizácia v `RecipientService`
- **Súbor:** `app/Services/RecipientService.php` (riadky 39–41, 49–51), `app/Http/Controllers/RecipientController.php` (riadky 76, 86)
- Controller volá `$this->authorize('update', $recipient)` cez policy, ale service znova robí manuálny `abort(403)` check. Zmätočné a ťažko udržiavateľné.
- **Návrh:** Odstrániť `abort(403)` zo servisu, nechať autorizáciu čisto na controlleri + policy.

### 2.3 `UserCompanyLogo::getUrlAttribute()` bez sanitizácie
- **Súbor:** `app/Models/UserCompanyLogo.php` (riadok 27–29)
- Vracia `/storage/` + `$this->link` bez kontroly path traversal.
- **Návrh:** Použiť `Storage::url()` namiesto manuálnej konštrukcie.

### 2.4 Chýba autorizácia v `InvoiceController::store()` na service vrstve
- **Súbor:** `app/Http/Controllers/InvoiceController.php` (riadok 63–71), `app/Services/InvoiceService.php` (riadok 103–173)
- `StoreInvoiceRequest` validuje `recipient_id` cez `Rule::exists`, ale service vrstva nekontroluje vlastníctvo.
- **Návrh:** Defense-in-depth — pridať check aj v servise.

---

## 3. Databáza a migrácie

### 3.1 `Schema::hasTable()` guardy v migráciách
- **Súbory:** všetky migrácie v `database/migrations/`
- Anti-pattern — migrácie by mali byť idempotentné cez migračný systém, nie cez runtime checky. Sťažuje rollbacky.
- **Návrh:** Odstrániť guardy, prípadne squashnúť migrácie do jednej čistej schémy.

### 3.2 Seed data v migráciách
- **Súbory:** `database/migrations/2026_03_08_000009_create_currencies_table.php`, `2026_03_08_000015_create_invoice_statuses_table.php`, `2026_03_08_000011_create_invoice_colors_table.php`
- Migrácie vkladajú data (`DB::table(...)->insert(...)`) — toto patrí do seederov. Seedery na to isté už existujú (`CurrencySeeder`, `InvoiceStatusSeeder`, `InvoiceColorSeeder`), takže je to duplicita.
- **Návrh:** Odstrániť inserty z migrácií, nechať len v seederoch.

### 3.3 Prekomplikovaná migrácia `add_fk_columns_to_users_table`
- **Súbor:** `database/migrations/2026_03_08_000012_add_fk_columns_to_users_table.php`
- Raw SQL `ALTER TABLE`, manuálne checky `information_schema.TABLE_CONSTRAINTS`, defensive kódovanie pre existenciu stĺpcov.
- **Návrh:** Squashnúť do jednej čistej migrácie pre users tabuľku.

### 3.4 Nekonzistentný názov tabuľky `users_companies_logo`
- **Súbor:** `database/migrations/2026_03_08_000010_create_users_companies_logo_table.php`, `app/Models/UserCompanyLogo.php`
- Gramaticky nesprávny názov. Model musí explicitne nastavovať `$table`.
- **Návrh:** Premenovať na `user_company_logos` (Laravel konvencia).

### 3.5 `recipients` tabuľka nemá `SoftDeletes`
- **Súbor:** `database/migrations/2026_03_08_000013_create_recipients_table.php`, `app/Models/Recipient.php`
- `Invoice` a `InvoiceItem` majú SoftDeletes, ale `Recipient` nie. Zmazanie recipienta nastaví FK na null.
- **Návrh:** Zvážiť pridanie SoftDeletes na `Recipient`, alebo zdokumentovať prečo nie.

### 3.6 `vat_types` nemá seed data v migrácii ani nie je volaný v `DatabaseSeeder`
- **Súbor:** `database/migrations/2026_03_08_000014_create_vat_types_table.php`, `database/seeders/VatTypeSeeder.php`
- Ak sa zabudne zavolať seeder, DPH výpočty nebudú fungovať.
- **Návrh:** Pridať `VatTypeSeeder` do `DatabaseSeeder`.

---

## 4. Business logika

### 4.1 DPH výpočet — floating point zaokrúhľovanie
- **Súbor:** `app/Services/InvoiceService.php` (riadky 107–115 a 142–159)
- Riadkové sumy sú `round()`-ované, ale `$vatTotal` nie je roundovaný per-riadok pred pripočítaním k celku. Môže spôsobiť 1-centový rozdiel medzi súčtom riadkov a celkovým VAT.
- **Návrh:** Zaokrúhľovať VAT per-riadok pred akumuláciou: `$vatTotal += round($vatAmount, 2);`

### 4.2 VAT rate parsovaný z textového kódu
- **Súbor:** `app/Services/InvoiceService.php` (riadok 83), `app/Models/VatType.php`
- `(float) $vatType->code` — ak kód nie je číslo (napr. `"standard"`), vráti `0.0` bez chyby.
- **Návrh:** Pridať `rate` (decimal) stĺpec do `vat_types` tabuľky. Kód nech zostane len ako identifikátor.

### 4.3 Duplikovaná VAT logika medzi backendom a frontendom
- **Súbory:** `app/Services/InvoiceService.php` (riadky 74–86), `resources/js/Components/InvoiceItemsTable.vue` (riadky 85–95)
- Rovnaká logika (MIMO/OSVO check, rate parsing) existuje na dvoch miestach. Zmena na jednom mieste sa ľahko zabudne na druhom.
- **Návrh:** Aspoň zdokumentovať, ideálne mať testy na obe strany.

### 4.4 Duplikovaný výpočet riadkov v `createInvoice()`
- **Súbor:** `app/Services/InvoiceService.php` (riadky 110–115 a 142–159)
- Items sa iterujú dvakrát — raz pre totály faktúry, raz pre uloženie `InvoiceItem`.
- **Návrh:** Zjednotiť do jedného loopu, akumulovať totály počas vytvárania itemov.

### 4.5 Definícia „awaiting" zahŕňa draft faktúry
- **Súbor:** `app/Services/InvoiceService.php` (riadok 53)
- `awaiting = total - paid - overdue` — draft faktúra nie je paid ani overdue, takže spadne do „awaiting payment".
- **Návrh:** Vylúčiť draft faktúry z awaiting výpočtu.

### 4.6 `getSuggestedNumber()` nie je race-condition safe
- **Súbor:** `app/Services/InvoiceService.php` (riadky 88–101)
- Medzi `count()` a `Invoice::create()` môže iný request vytvoriť faktúru s rovnakým číslom. Unique constraint to zachytí, ale user dostane generickú chybu.
- **Návrh:** Retry logika pri unique violation, alebo pesimistický lock.

### 4.7 `DuplicateInvoiceNumberException` sa nikde nepoužíva
- **Súbor:** `app/Exceptions/DuplicateInvoiceNumberException.php`
- Existuje, ale nikde v kóde sa nevyhadzuje.
- **Návrh:** Použiť v `createInvoice()` pri unique violation, alebo odstrániť.

---

## 5. Frontend — Vue komponenty

### 5.1 `BillingDetailsForm` je príliš komplexná
- **Súbor:** `resources/js/Components/BillingDetailsForm.vue` (360 riadkov)
- Dva módy (`profile` vs `embed`), interný aj externý form, voliteľný `<form>` wrapper, countries list, InvoiceSettings embedding.
- **Návrh:** Rozbiť na `ProfileBillingForm` a `IssuerDetailsSummary`.

### 5.2 Duplicitné props pre `invoiceColors` / `invoice_colors`
- **Súbory:** `resources/js/Pages/Invoices/Create.vue` (riadky 38–44), `resources/js/Components/InvoiceSettings.vue` (riadky 29–36)
- Defensive coding pre Inertia snake/camel case — akceptujú sa obe varianty.
- **Návrh:** Normalizovať na jednom mieste (controller alebo Inertia plugin).

### 5.3 `Invoices/Edit.vue` je prázdny stub
- **Súbor:** `resources/js/Pages/Invoices/Edit.vue`
- Route existuje a je prístupná, ale stránka zobrazuje len „Edit form – to be implemented."
- **Návrh:** Implementovať, alebo dočasne redirect na invoices index / zobraziť readonly detail.

### 5.4 Frontend validácia nekonzistentná s backendom
- **Súbor:** `resources/js/Pages/Invoices/Create.vue` (riadky 178–189), `app/Http/Requests/StoreInvoiceRequest.php`
- Frontend nekontroluje `due_date >= issue_date`, `items.*.quantity >= 0`, max dĺžky.
- **Návrh:** Buď zrkadliť backend pravidlá, alebo zjednodušiť frontend validáciu len na UX hint a spoliehať sa na backend.

### 5.5 Deep watch na items array
- **Súbor:** `resources/js/Components/InvoiceItemsTable.vue` (riadky 58–67)
- `watch(items, ..., { deep: true })` na array of objects. Pre malý počet OK, ale neškáluje.
- **Návrh:** Pre veľký počet položiek zvážiť debounce alebo manuálne triggery.

### 5.6 Invoice linky na recipient detail smerujú na index
- **Súbor:** `resources/js/Pages/Recipients/Show.vue` (riadok 46)
- `route('invoices')` namiesto linku na konkrétnu faktúru.
- **Návrh:** Linkovať na detail/edit faktúry: `route('invoices.edit', inv.id)`.

### 5.7 `Recipients/Edit.vue` — strata pôvodnej hodnoty `name`
- **Súbor:** `resources/js/Pages/Recipients/Edit.vue` (riadky 15–16)
- `name` aj `company_name` sa inicializujú na `props.recipient.company_name ?? props.recipient.name`. Pôvodná hodnota `name` sa stráca ak existuje `company_name`.
- **Návrh:** Inicializovať každé pole zvlášť: `name: props.recipient.name ?? ''`.

### 5.8 Chýba zobrazenie flash messages
- **Súbory:** `resources/js/Layouts/AuthenticatedLayout.vue`, controllery (posielajú `->with('success', '...')`)
- Backend posiela flash messages, ale v layoute nikde nie je toast/notification komponent.
- **Návrh:** Pridať notification komponent do `AuthenticatedLayout`, čítať `$page.props.flash`.

---

## 6. Testy

### 6.1 Žiadne testy na doménovú logiku
- **Adresár:** `tests/`
- Existujú len default Breeze testy (auth, profile). Žiadne testy na:
  - Invoice CRUD
  - DPH výpočty a zaokrúhľovanie
  - PDF generovanie
  - RecipientService
  - Policies
- **Návrh:** Pridať minimálne unit testy na DPH výpočty a feature testy na invoice CRUD flow.

---

## 7. Drobnosti a code style

### 7.1 Mŕtvy kód v `InvoiceController`
- **Súbor:** `app/Http/Controllers/InvoiceController.php` (riadky 73–76, 88–91)
- `show()` a `update()` sú definované ako `never` s `abort(404)`, ale nemajú route v `web.php`.
- **Návrh:** Odstrániť, keďže nie sú súčasťou resource route.

### 7.2 Zbytočná validácia v `InvoiceService::updateStatus()`
- **Súbor:** `app/Services/InvoiceService.php` (riadky 175–182)
- `InvoiceStatus::where('id', ...)->exists()` — `UpdateInvoiceStatusRequest` už validuje `exists:invoice_statuses,id`.
- **Návrh:** Odstrániť duplikovaný check v servise.

### 7.3 Zlé poradie operácií v `ProfileService::deleteAccount()`
- **Súbor:** `app/Services/ProfileService.php` (riadky 72–76)
- `Auth::logout()` sa volá pred `$user->delete()`. Ak delete zlyhá, user je odhlásený ale účet existuje.
- **Návrh:** Najprv `$user->delete()`, potom `Auth::logout()`, ideálne v transakcii.

### 7.4 Try/catch v `HandleInertiaRequests::share()` maskuje chyby
- **Súbor:** `app/Http/Middleware/HandleInertiaRequests.php` (riadky 44–48)
- Catch na `companyLogo` ticho zožerie výnimku.
- **Návrh:** Pridať logovanie do catch bloku.

### 7.5 Nekonzistentný naming
- `users_companies_logo` tabuľka vs `UserCompanyLogo` model
- `recipient_state` na faktúre obsahuje ZIP+state (viď `Create.vue` riadok 87: `[r.state, r.zip].filter(Boolean).join(', ')`), ale na recipientovi sú to oddelené stĺpce (`state`, `zip`)
- Niekde `company_name`, niekde `name` pre ten istý koncept

### 7.6 `InvoiceStatus` kódy — chýba `CODE_SENT` a `CODE_OVERDUE`
- **Súbor:** `app/Models/InvoiceStatus.php`
- Definované sú len `CODE_DRAFT` a `CODE_PAID`, ale v seederi existujú aj `sent` a `overdue`.
- **Návrh:** Pridať konštanty pre všetky statusy.
