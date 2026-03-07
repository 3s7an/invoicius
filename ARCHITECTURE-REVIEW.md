# Prehľad architektúry

Tento dokument sumarizuje oblasti na zlepšenie z hľadiska architektúry a kódu.

---

## 1. Mŕtvy / nekonzistentný kód

- **`InvoiceController::show()` a `update()`** sú prázdne. Buď ich odstráň (ak sa nepoužívajú), alebo doplň správanie. Prázdne metódy sú mätúce a môžu viesť k 404 alebo neočakávanému správaniu podľa rout.
- **`InvoiceService::findForUserOrFail()`** – už sa nikde nevolá (po zavedení policies). Buď ho odstráň, alebo ho používaj na miestach, kde potrebuješ „nájdi alebo 403“ bez route model bindingu (napr. v joboch).

---

## 2. Referenčné dáta a „fat“ kontrolér

V **`InvoiceController::index()`** a **`create()`** sa priamo načítavajú modely ako `Currency`, `VatType`, `InvoiceColor`, `InvoiceStatus`:

- Kontrolér by nemal vedieť o všetkých týchto modeloch a ich dotazoch.
- **Odporúčanie:** jedna metóda v servise (napr. `InvoiceService::getCreateFormData(int $userId)` alebo samostatný `InvoiceFormDataService`), ktorá vráti `currencies`, `vat_types`, `invoice_colors`, `default_currency_id`, `suggested_number`, `recipients`, `preselected_recipient`. Kontrolér len zavolá servis a predá dáta do view. To isté pre `index()` – zoznam `invoice_statuses` môže ísť zo servisu.

---

## 3. Magic strings pre stavy

V **`InvoiceService`**:

- `InvoiceStatus::where('code', 'paid')->first()?->id`
- `InvoiceStatus::where('code', 'draft')->first()?->id`

Kód závisí od reťazcov `'paid'` a `'draft'`. Lepšie:

- konštanty na **`InvoiceStatus`** (napr. `STATUS_PAID`, `STATUS_DRAFT`), alebo
- enum (PHP 8.1+) `InvoiceStatusEnum` s prípadnou mapou na DB kódy,

a v servise používať tieto konštanty/enum namiesto raw stringov. Zníži to chybovosť a zlepší refaktoring.

---

## 4. Chýbajúca unikátnosť čísla faktúry

V migrácii je `$table->index(['user_id', 'number'])`, nie **unique**.

- Dva súčasné requesty môžu vytvoriť dve faktúry s rovnakým `number` pre toho istého usera.
- **Odporúčanie:** unique index `['user_id', 'number']` a v `StoreInvoiceRequest` (alebo v servise) pravidlo `Rule::unique('invoices', 'number')->where('user_id', $userId)`. Prípadne lock / „select for update“ pri generovaní čísla, ak budeš generovať v transakcii.

---

## 5. Servisná vrstva a HTTP

- **`ProfileService::deleteAccount(User $user, Request $request)`** – servis závisí od `Illuminate\Http\Request`. To spája doménovú logiku s HTTP vrstvou a sťažuje testovanie a použitie mimo web requestu.
- **Odporúčanie:** v kontroléri po validácii invalidate session a regenerate token (zostane to v HTTP vrstve) a v servise nechaj len `logout()` a `$user->delete()`. Prípadne predaj do servisu SessionGuard alebo rozhranie, nie celý `Request`.

---

## 6. Vstup do servisu a zmluva

- **`InvoiceService::createInvoice(int $userId, array $validated)`** berie „čo príde z requestu“. Štruktúra (`recipient.*`, `items.*`) je viazaná na Form Request; servis je ťahaný k zmene každého poľa vo formulári.
- **Odporúčanie:** DTO / value object (napr. `CreateInvoiceData`) s typovanými vlastnosťami (recipient, položky, sumy, atď.). Kontrolér/Request naplní DTO z `$request->validated()`, servis berie len DTO. Zmluva bude jasná, testovanie jednoduchšie a zmeny requestu nebudú priamo pretekať do servisu.

---

## 7. Exception handling

- V **`InvoiceController::store()`** sa chytá `\Throwable` a vracia generickú hlášku „Something went wrong“. Výnimka sa po zalogovaní v servise znovu hádže.
- Chýba **centrálna obsluha** v `bootstrap/app.php` → `withExceptions()`: napr. `report()` pre logovanie, `render()` pre doménové výnimky (napr. `DuplicateInvoiceNumberException`) s vhodnou HTTP odpoveďou a správou. Kontrolér by nemusel mať try/catch; buď nechaj výnimku prejsť, alebo hádž doménovú výnimku a rieš ju v handleri.

---

## 8. Duplicita autorizácie

V **`InvoiceService`** metódy `updateStatus()` a `delete()` stále kontrolujú `$invoice->user_id !== $userId` a volajú `abort(403)`.

- V kontroléri už voláš `$this->authorize(...)`, takže táto kontrola v servise je **duplicitná**.
- **Odporúčanie:** buď odstráň kontrolu zo servisu (jediný zdroj pravdy v Policy), alebo ju nechaj zámerne ako „defense in depth“ a zdokumentuj to (napr. komentárom). Bez toho to pôsobí ako nekonzistentná vrstva autorizácie.

---

## 9. Rozhrania pre servisy

- `InvoiceService`, `RecipientService`, `ProfileService` sú konkrétne triedy. Pri testovaní kontrolérov ich musíš mockovať cez konkrétnu triedu.
- **Odporúčanie:** interface (napr. `InvoiceServiceInterface`) v `app/Contracts/` a binding v `AppServiceProvider`. Kontroléry závisia od rozhrania. Testy potom injektujú fake implementáciu; v produkcii sa zviaže skutočný servis.

---

## 10. Konzistencia `auth()->id()` vs `$request->user()->id`

- Niektoré miesta používajú `auth()->id()` (napr. `RecipientController::index()`, `store()`), iné `$request->user()->id`.
- **Odporúčanie:** tam, kde máš Request, používaj `$request->user()->id` (explicitný zdroj). Tam, kde Request nemáš (napr. v servise volanom z jobu), je `auth()->id()` nevhodný – tam by mal byť user id vždy **argumentom**.

---

## 11. Testovanie doménovej logiky

- V `tests/` sú hlavne Breeze testy (auth, profile). Chýbajú **unit testy pre** `InvoiceService` (výpočet DPH, `getSuggestedNumber`, štatistiky) a **feature testy** pre vytvorenie faktúry, zmenu stavu, PDF, policies.
- Bez toho je refaktoring rizikový a zmeny ťažko overiteľné.

---

## 12. Drobnosti

- **`StoreInvoiceRequest`** validuje `issuer` a `issuer.name`, ale **`createInvoice()`** tieto dáta nepoužíva. Ak sú len na zobrazenie, odporúčam to v requeste/servise zdokumentovať; ak mali ísť do DB, je to chýbajúca funkcionalita.
- **`InvoiceController::downloadPdf()`** nemá návratový typ – vracia `PdfBuilder`. Buď doplň vhodný return type (ak to Spatie umožňuje), alebo `: mixed` + krátky komentár, aby bolo jasné, čo metóda vracia.
- **Order of use / formátovanie** – v niektorých súboroch je poradie `use` alebo formátovanie nekonzistentné; malá vec, ale pomáha to napr. pri code style (PHP-CS-Fixer / Laravel Pint).

---

## Zhrnutie priorít

| Priorita | Čo riešiť |
|----------|------------|
| Vysoká | Unikátnosť `(user_id, number)` pre faktúry + validácia; prázdne `show()`/`update()` alebo ich odstrániť |
| Vysoká | Presun referenčných dát (currencies, vat_types, atď.) do servisu; zníženie závislostí kontroléra |
| Stredná | Magic strings pre statusy → konštanty/enum; `deleteAccount` bez závislosti na `Request` |
| Stredná | DTO pre vytvorenie faktúry; centrálny exception handling |
| Nižšia | Rozhrania pre servisy; konzistencia auth vs request; testy pre InvoiceService a feature flow |
