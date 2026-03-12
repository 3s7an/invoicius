# Automatizácia generovania faktúr (n8n integrácia)

## Prehľad

Nová funkcionalita umožní používateľovi nastaviť automatické generovanie faktúr pre vybraného recipienta. Systém prostredníctvom **n8n workflow** denne kontroluje naplánované automatizácie a pri zhode s aktuálnym dátumom automaticky vytvorí faktúru a odošle notifikačný email. Vygenerovaná faktúra sa následne dá manuálne doeditovať (cena, merná jednotka).

---

## 1. Nová entita: `Automatization`

| Stĺpec | Typ | Popis |
|---------|-----|-------|
| `id` | `bigint` (PK) | |
| `user_id` | `foreignId` | Vlastník automatizácie |
| `recipient_id` | `foreignId` (nullable) | Cieľový recipient (ak to daný typ vyžaduje) |
| `type` | `string` (voľný text, nie DB enum) | Kľúč typu automatizácie (napr. `invoice_auto_gen`). Mapuje sa na konkrétnu Handler triedu. |
| `date_trigger` | `date` | Dátum najbližšieho spustenia (po každom behu sa automaticky posunie o +1 mesiac) |
| `is_active` | `boolean` | Či je automatizácia aktívna |
| `last_run_at` | `timestamp` (nullable) | Kedy naposledy prebehla |
| `payload` | `json` (nullable) | Vstupná konfigurácia špecifická pre daný typ (handler si ju číta sám) |
| `result_data` | `json` (nullable) | Výstupné dáta z posledného behu (napr. `invoice_id`, `invoice_number`) |
| `timestamps` | | created_at, updated_at |
| `soft_deletes` | | deleted_at |

### Vzťahy (Eloquent)

- `Automatization` → `belongsTo` User
- `Automatization` → `belongsTo` Recipient (nullable)
- `User` → `hasMany` Automatization
- `Recipient` → `hasMany` Automatization

---

## 2. Architektúra automatizácií (Strategy Pattern)

Každý typ automatizácie (`type` string) sa mapuje na vlastnú **Handler** triedu. `AutomatizationService` je orchestrátor, ktorý pre každú due automatizáciu resolvne správny handler a deleguje na neho.

### Princípy

- **Open/Closed** — nový typ = nová Handler trieda, žiadna úprava existujúceho kódu
- **Single Responsibility** — každý handler robí jednu vec
- **DRY** — spoločná logika (nájdi due, zavolaj handler, posuň trigger, ulož result) je v `AutomatizationService`

### Štruktúra súborov

```
app/
├── Contracts/
│   └── AutomatizationHandlerInterface.php
├── Automatizations/
│   └── Handlers/
│       ├── InvoiceAutoGenHandler.php        ← prvý handler
│       └── ...                              ← budúce handlery
├── DTOs/
│   └── AutomatizationResult.php
├── Services/
│   └── AutomatizationService.php            ← orchestrátor
```

### Interface

```php
interface AutomatizationHandlerInterface
{
    public function type(): string;

    public function handle(Automatization $automatization): AutomatizationResult;
}
```

### Result DTO

```php
class AutomatizationResult
{
    public function __construct(
        public readonly bool $success,
        public readonly array $data = [],
        public readonly ?string $error = null,
    ) {}
}
```

### Príklad: `InvoiceAutoGenHandler`

```php
class InvoiceAutoGenHandler implements AutomatizationHandlerInterface
{
    public function __construct(
        private readonly InvoiceServiceInterface $invoiceService,
    ) {}

    public function type(): string
    {
        return 'invoice_auto_gen';
    }

    public function handle(Automatization $automatization): AutomatizationResult
    {
        $invoice = $this->invoiceService->generateFromAutomatization(
            $automatization->user_id,
            $automatization->recipient_id,
        );

        return new AutomatizationResult(
            success: true,
            data: [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->number,
                'user_email' => $automatization->user->email,
                'recipient_name' => $automatization->recipient->name,
            ],
        );
    }
}
```

### Orchestrátor: `AutomatizationService`

```php
class AutomatizationService
{
    /** @var array<string, AutomatizationHandlerInterface> */
    private array $handlers = [];

    public function registerHandler(AutomatizationHandlerInterface $handler): void
    {
        $this->handlers[$handler->type()] = $handler;
    }

    public function processDueAutomatizations(): array
    {
        $due = Automatization::dueToday()->where('is_active', true)->get();
        $results = [];

        foreach ($due as $automatization) {
            $handler = $this->resolveHandler($automatization->type);
            $result = $handler->handle($automatization);

            if ($result->success) {
                $this->markAsRun($automatization, $result);
            }

            $results[] = $result;
        }

        return $results;
    }

    private function resolveHandler(string $type): AutomatizationHandlerInterface
    {
        return $this->handlers[$type]
            ?? throw new \InvalidArgumentException("No handler registered for type: {$type}");
    }

    private function markAsRun(Automatization $automatization, AutomatizationResult $result): void
    {
        $automatization->update([
            'last_run_at' => now(),
            'date_trigger' => $automatization->date_trigger->addMonth(),
            'result_data' => $result->data,
        ]);
    }

    // ... CRUD metódy (store, update, delete, listForUser) ...
}
```

### Registrácia v `AppServiceProvider`

```php
$this->app->singleton(AutomatizationService::class, function ($app) {
    $service = new AutomatizationService();
    $service->registerHandler($app->make(InvoiceAutoGenHandler::class));
    // budúce typy:
    // $service->registerHandler($app->make(ReminderEmailHandler::class));
    // $service->registerHandler($app->make(RecurringReportHandler::class));
    return $service;
});
```

### Pridanie nového typu automatizácie — 3 kroky

1. Vytvoriť handler triedu implementujúcu `AutomatizationHandlerInterface`
2. Zaregistrovať v `AppServiceProvider`
3. Hotovo — žiadna migrácia, žiadna úprava existujúceho kódu

---

## 3. n8n Workflow

```
┌─────────────────┐
│   CRON (24h)    │  Spúšťa sa raz denne (napr. 06:00)
└────────┬────────┘
         │
         ▼
┌───────────────────────────────────────────────────────┐
│  HTTP Request → POST /api/automatizations/process      │
│  (autentifikácia cez Bearer token)                     │
│                                                        │
│  Laravel interne:                                      │
│   1. Nájde automatizácie kde date_trigger = dnes       │
│   2. Pre každú vytvorí faktúru (1 prázdny item)        │
│   3. Posunie date_trigger o +1 mesiac                  │
│   4. Vráti zoznam vygenerovaných faktúr + emaily userov│
└────────┬──────────────────────────────────────────────-┘
         │
         ▼
┌─────────────────┐
│   IF / Switch   │  Ak boli vygenerované faktúry
└────────┬────────┘
         │
         ▼
┌──────────────────────────────────────────────┐
│  n8n Email node (Send Email)                  │
│  → notifikácia používateľovi o vygenerovanej  │
│    faktúre (priamo cez n8n, nie cez Laravel)  │
└──────────────────────────────────────────────┘
```

n8n posiela **1 HTTP request**, celá logika (vyhľadanie due automatizácií, generovanie faktúr, posun triggeru) beží v Laraveli. Response obsahuje zoznam výsledkov, z ktorých n8n vie poskladať emaily.

---

## 4. Nové API endpointy

### Autentifikácia

API endpointy pre n8n sú chránené cez **Header Auth** — n8n posiela vlastný header s tokenom, Laravel ho overuje oproti hodnotám v `.env`. Bez middleware — validácia cez zdieľaný trait `VerifiesN8nRequests`.

#### Tok autentifikácie

```
n8n HTTP Request
  → posiela header "n8n-user: H6uVSAnYIw"
  → Laravel číta header podľa N8N_USER env ("n8n-user")
  → porovná hodnotu headeru s N8N_TOKEN env ("H6uVSAnYIw")
  → ak nesedí → 401 Unauthorized
```

#### `.env`

```env
N8N_USER=n8n-user
N8N_TOKEN=H6uVSAnYIw
```

#### `config/services.php`

```php
'n8n' => [
    'header_name' => env('N8N_USER'),
    'token' => env('N8N_TOKEN'),
],
```

#### Trait `VerifiesN8nRequests`

Zdieľaný trait pre všetky API controllery, ktoré prijímajú requesty z n8n.

```php
// app/Http/Traits/VerifiesN8nRequests.php
namespace App\Http\Traits;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait VerifiesN8nRequests
{
    protected function verifyN8nRequest(Request $request): void
    {
        $headerName = config('services.n8n.header_name');
        $expectedToken = config('services.n8n.token');

        if (!$headerName || !$expectedToken) {
            throw new HttpException(500, 'N8N credentials not configured.');
        }

        if ($request->header($headerName) !== $expectedToken) {
            throw new HttpException(401, 'Invalid n8n credentials.');
        }
    }
}
```

#### Použitie v controlleri

```php
class AutomatizationController extends Controller
{
    use VerifiesN8nRequests;

    public function process(Request $request): JsonResponse
    {
        $this->verifyN8nRequest($request);

        $results = $this->automatizationService->processDueAutomatizations();
        // ...
    }
}
```

Každý budúci API controller pre n8n len pridá `use VerifiesN8nRequests` a zavolá `$this->verifyN8nRequest($request)` na začiatku metódy.

#### n8n strana — nastavenie credentials

1. V n8n: **Credentials** → **Add Credential** → **Header Auth**
2. Nastav:
   - **Name** (header name): `n8n-user` (musí sedieť s `N8N_USER` v `.env`)
   - **Value**: `H6uVSAnYIw` (musí sedieť s `N8N_TOKEN` v `.env`)
3. V HTTP Request node: **Authentication** → **Generic Credential Type** → **Header Auth** → vyber vytvorený credential
4. **Allowed HTTP Request Domains**: môžeš obmedziť na doménu tvojej appky

n8n ukladá credentials šifrovane — nie sú viditeľné v definícii workflow, len sa referencujú.

### Endpointy

#### API endpointy (n8n)

| Metóda | URI | Popis |
|--------|-----|-------|
| `POST` | `/api/automatizations/process` | Spracuje všetky due automatizácie naraz — vytvorí faktúry, posunie triggery, vráti výsledky |

#### Web endpointy (Dashboard CRUD)

| Metóda | URI | Popis |
|--------|-----|-------|
| `GET` | `/api/automatizations` | Zoznam automatizácií pre dashboard |
| `POST` | `/api/automatizations` | Vytvorenie novej automatizácie |
| `PATCH` | `/api/automatizations/{id}` | Úprava automatizácie |
| `DELETE` | `/api/automatizations/{id}` | Zmazanie automatizácie |

### `POST /api/automatizations/process` — Response

Každý výsledok obsahuje `type` a `data` z handlera (`AutomatizationResult`). Štruktúra `data` je špecifická pre daný typ.

```json
{
  "processed": 2,
  "results": [
    {
      "automatization_id": 1,
      "type": "invoice_auto_gen",
      "success": true,
      "data": {
        "invoice_id": 42,
        "invoice_number": "20260312",
        "user_email": "user@example.com",
        "recipient_name": "Firma s.r.o."
      },
      "next_trigger": "2026-04-12"
    }
  ]
}
```

Pre každú due automatizáciu Laravel interne:
- Vytvorí faktúru s **1 prázdnym invoice itemom** (name = "", unit = "", quantity = 1, unit_price = 0) — používateľ ho doedituje manuálne (cena, MJ)
- Použije daň (DPH) podľa profilu: `23%` / `5%` / `MIMO` (nový stĺpec v User profile → `default_vat_type_id`)
- Použije predvolenú menu (`currency_id` z User)
- Vyplní fakturačné údaje odosielateľa (adresa, IČO, DIČ, IČ DPH, IBAN)
- Vyplní údaje recipienta z DB
- Automaticky vygeneruje číslo faktúry (`getSuggestedNumber`)
- Nastaví status: `draft`
- Posunie `date_trigger` na automatizácii o **+1 mesiac**

---

## 5. Zmeny v backende

### 5.1 Migrácia

- `create_automatizations_table` — nová tabuľka podľa špecifikácie vyššie
- `add_default_vat_type_to_users_table` — pridať `default_vat_type_id` do users tabuľky

### 5.2 Model

- Nový model `Automatization` s reláciami, `$casts` pre `payload` a `result_data` (JSON), scope `forUser($userId)`, `scopeDueToday()`
- Aktualizovať `User` model — pridať `hasMany` automatizations, `belongsTo` defaultVatType
- Aktualizovať `Recipient` model — pridať `hasMany` automatizations

### 5.3 Policy

- Nová `AutomatizationPolicy` — overenie vlastníctva (user_id)

### 5.4 Service + Handlers

- **`AutomatizationService`** — orchestrátor s registrovanými handlermi (viď sekcia 2)
- **`InvoiceAutoGenHandler`** — prvý handler, deleguje na `InvoiceService`
- Rozšíriť **`InvoiceService`** — nová metóda `generateFromAutomatization($userId, $recipientId)` na vytvorenie faktúry s 1 prázdnym invoice itemom a default hodnotami z profilu

### 5.5 Controller

- **`Api\AutomatizationController`** — `process()` endpoint pre n8n + CRUD pre dashboard
- **`AutomatizationController`** (web) — pre Inertia/dashboard operácie

### 5.6 Request validácia

- `StoreAutomatizationRequest` — validácia pre vytvorenie
- `UpdateAutomatizationRequest` — validácia pre úpravu

### 5.7 DTO

- `CreateAutomatizationData` — pre service vrstvu (CRUD)
- `AutomatizationResult` — výstup z handlera (viď sekcia 2)

### 5.8 Routes

```php
// routes/api.php (nový súbor)
// Bez middleware — autentifikácia cez X-API-Key header priamo v controlleri
Route::post('/automatizations/process', [Api\AutomatizationController::class, 'process']);

// routes/web.php (rozšírenie)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('automatizations', AutomatizationController::class)
        ->only(['store', 'update', 'destroy']);
});
```

### 5.9 Konfigurácia (.env)

```env
N8N_USER=n8n-user
N8N_TOKEN=H6uVSAnYIw
```

Tieto hodnoty musia sedieť s Header Auth credentials v n8n (viď sekcia 4 — Autentifikácia).

---

## 6. Zmeny vo frontende

### 6.1 Dashboard — panel automatizácií

Rozšíriť `Dashboard.vue` o novú sekciu pod existujúcimi stats kartami:

- **Zoznam aktívnych automatizácií** — tabuľka/karty s:
  - Meno recipienta
  - Typ automatizácie
  - Dátum spustenia (`date_trigger`)
  - Status (aktívna / neaktívna)
  - Posledné spustenie
  - Akcie (editovať, zmazať, deaktivovať)
- **Tlačidlo „Pridať automatizáciu"** — otvorí modálne okno / formulár

### 6.2 Nové komponenty

- **`AutomatizationList.vue`** — tabuľka aktívnych automatizácií (PrimeVue DataTable)
- **`AutomatizationForm.vue`** — formulár na vytvorenie/editáciu (modál):
  - Výber recipienta (dropdown)
  - Typ automatizácie (select)
  - Dátum spustenia (date picker)
  - Toggle aktívne/neaktívne

### 6.3 Rozšírenie DashboardController

Pridať do `index()` metódy dáta o automatizáciách pre prihlaseného usera.

### 6.4 Profile — default DPH

Rozšíriť sekciu **Billing details** v `Profile/Edit.vue`:

**Frontend (`BillingDetailsForm.vue`):**
- Nový dropdown „Predvolená DPH" s hodnotami z `VatType` (23%, 5%, MIMO)
- Hodnota sa posiela spolu s ostatnými billing details cez existujúci `PATCH /profile/details`

**Backend:**
- `ProfileController::updateDetails()` — pridať `default_vat_type_id` do ukladaných polí
- `ProfileService::getEditData()` — pridať `vat_types` do dát pre frontend
- Validácia `default_vat_type_id` v existujúcom request (nullable, exists:vat_types,id)

---

## 7. Poradie implementácie

| # | Úloha | Priorita |
|---|-------|----------|
| 1 | Migrácie (`automatizations` s `payload`/`result_data` JSON, `default_vat_type_id` na users) | 🔴 Vysoká |
| 2 | Model `Automatization` + relácie + aktualizácia User/Recipient modelov | 🔴 Vysoká |
| 3 | `AutomatizationHandlerInterface` + `AutomatizationResult` DTO | 🔴 Vysoká |
| 4 | `InvoiceAutoGenHandler` + rozšírenie `InvoiceService` — `generateFromAutomatization()` | 🔴 Vysoká |
| 5 | `AutomatizationService` (orchestrátor + CRUD) + registrácia handlera v ServiceProvider | 🔴 Vysoká |
| 6 | `AutomatizationPolicy` | 🔴 Vysoká |
| 7 | API routes + controllery + Sanctum autentifikácia | 🔴 Vysoká |
| 8 | Web controller + routes pre CRUD automatizácií | 🟡 Stredná |
| 9 | Frontend — Dashboard panel automatizácií | 🟡 Stredná |
| 10 | Frontend — formulár na pridanie automatizácie | 🟡 Stredná |
| 11 | Frontend — default DPH v profile | 🟡 Stredná |
| 12 | n8n workflow setup + testovanie | 🟢 Nízka |
| 13 | n8n Email node — notifikácia o vygenerovanej faktúre | 🟢 Nízka |
| 14 | Testy (Feature + Unit) | 🔴 Vysoká |

---
